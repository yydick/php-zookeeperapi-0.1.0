<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Spool\Zookeeper\Lib;

use Swoole\Client;
use Spool\Zookeeper\Adaptor\AuthListHead;
use Spool\Zookeeper\Adaptor\BufferListT;
use Spool\Zookeeper\Adaptor\BufferHeadT;
use Spool\Zookeeper\Adaptor\CompletionHeadT;
use Spool\Zookeeper\Lib\ClientIdT;
use Spool\Zookeeper\Adaptor\PrimeStruct;
use Spool\Zookeeper\Classes\Oarchive;
use Spool\Zookeeper\Classes\Iarchive;
use Spool\Zookeeper\Classes\AuthCompletionListT;
use Spool\Zookeeper\Lib\ZookeeperException;
use Spool\Zookeeper\Generated\RequestHeader;
use Spool\Zookeeper\Generated\WatcherEvent;
use Spool\Zookeeper\Generated\ReplyHeader;
use Spool\Zookeeper\Adaptor\CompletionListT;
use Spool\Zookeeper\Hashtable\WatcherObjectList;
use Spool\Zookeeper\Hashtable\Hashtable;

/**
 * Description of Zhandle
 *
 * @author 大天使长
 */
class ZhandleT {
    /**
     * @var \Swoole\Client
     */
    public $fd;			    /* the descriptor used to talk to zookeeper */
    /**
     * @var string
     */
    public $hostname;		    /* the hostname of zookeeper */
    /**
     * @var array string
     */
    public $addrs;		    /* the addresses that correspond to the hostname */
    /**
     * @var int
     */
    public $addrs_count;	    /* The number of addresses in the addrs array */
    /**
     * @var WatcherObjectList
     */
    public $watcher;		    /* the registered watcher */
    /**
     * @var Timeval
     */
    public $last_recv;		    /* The time that the last message was received */
    /**
     * @var Timeval
     */
    public $last_send;		    /* The time that the last message was sent */
    /**
     * @var Timeval
     */
    public $last_ping;		    /* The time that the last PING was sent */
    /**
     * @var Timeval
     */
    public $next_deadline;	    /* The time of the next deadline */
    /**
     * @var Timeval
     */
    public $recv_timeout;	    /* The maximum amount of time that can go by without 
     receiving anything from the zookeeper server */
    /**
     * @var BufferListT
     */
    public $input_buffer;	    /* the current buffer being read in */
    /**
     * @var BufferHeadT
     */
    public $to_process;		    /* The buffers that have been read and are ready to be processed. */
    /**
     * @var BufferHeadT
     */
    public $to_send;		    /* The packets queued to send */
    /**
     * @var CompletionHeadT
     */
    public $sent_requests;	    /* The outstanding requests */
    /**
     * @var CompletionHeadT
     */
    public $completions_to_process; /* completions that are ready to run */
    /**
     * @var int
     */
    public $connect_index;	    /* The index of the address to connect to */
    /**
     * @var ClientIdT
     */
    public $client_id;		    //obj ClientIdT
    /**
     * @var int
     */
    public $last_zxid;		    //long long
    /**
     * @var int
     */
    public $outstanding_sync = 0;	    /* Number of outstanding synchronous requests */
    /**
     * @var BufferListT
     */
    public $primer_buffer;	    /* The buffer used for the handshake at the start of a connection */
    /**
     * @var PrimeStruct
     */
    public $primer_storage;   /* the connect response */
    /**
     * @var char[40]
     */
    public $primer_storage_buffer = '';  /* the true size of primer_storage */
    /**
     * @var int
     */
    public $state;		    //int
    public $context;		    //void
    /**
     * @var AuthListHead
     */
    public $auth_h;		    /* authentication data list */
    /* zookeeper_close is not reentrant because it de-allocates the zhandler. 
     * This guard variable is used to defer the destruction of zhandle till 
     * right before top-level API call returns to the caller */
    public $ref_counter;	    //int32_t
    public $close_requested;	    //int
    public $adaptor_priv;	    //void
    /* Used for debugging only: non-zero value indicates the time when the zookeeper_process
     * call returned while there was at least one unprocessed server response 
     * available in the socket recv buffer */
    /**
     * @var Timeval
     */
    public $socket_readable;
    
    /**
     * @var array
     */
    public $active_node_watchers;   //array
    /**
     * @var array
     */
    public $active_exist_watchers;  //array
    /**
     * @var array
     */
    public $active_child_watchers;  //array
    /** used for chroot path at the client side **/
    /**
     * @var string
     */
    public $chroot;		    //string
    /**
     * @var int
     */
    public static $xid;
    public function api_prolog()
    {
        $this->inc_ref_counter(1);
    }
    public function api_epilog(int $rc) : int
    {
        if($this->inc_ref_counter(-1)==0 && $this->close_requested!=0){
            $this->close();
        }
        return $rc;
    }
    public function close() : int
    {
        $rc = ZOK;

        $this->close_requested = 1;
        if ($this->inc_ref_counter(1)>1) {
            /* We have incremented the ref counter to prevent the
             * completions from calling zookeeper_close before we have
             * completed the adaptor_finish call below. */

        /* Signal any syncronous completions before joining the threads */
            $this->enter_critical();
            $this->free_completions(1, ZCLOSING);
            $this->leave_critical();

            $this->adaptor_finish();
            /* Now we can allow the handle to be cleaned up, if the completion
             * threads finished during the adaptor_finish call. */
            $this->api_epilog(zh, 0);
            return ZOK;
        }
        /* No need to decrement the counter since we're just going to
         * destroy the handle later. */
        if($this->state == ZOO_CONNECTED_STATE){
	    $oa = new Oarchive();
	    $h = new RequestHeader($this->get_xid(), ZOO_CLOSE_OP);
            Log::LOG_INFO("Closing zookeeper sessionId=" . $this->client_id->client_id . " to ["
                    . $this->format_current_endpoint_info() . "]\n", __LINE__, __METHOD__);
            $rc = $oa->serialize_RequestHeader($oa, "header", $h);
            $rc = $rc < 0 ? $rc : $this->queue_buffer_bytes($this->to_send, $oa->get_buffer(), $oa->get_buffer_len());
            /* We queued the buffer, so don't free it */
            $oa->create_buffer_oarchive();
            if ($rc < 0) {
                $rc = ZMARSHALLINGERROR;
                throw new ZookeeperException($rc);
            }

            /* make sure the close request is sent; we set timeout to an arbitrary
             * (but reasonable) number of milliseconds since we want the call to block*/
            $rc = $this->adaptor_send_queue(3000);
        }else{
            Log::LOG_INFO("Freeing zookeeper resources for sessionId=" . $this->client_id->clientId . "\n",
                    __LINE__, __METHOD__);
            $rc = ZOK;
        }
    }
    protected function adaptor_finish() {
	$this->api_prolog();
	$this->api_epilog(0);
    }
    public function flush_send_queue(int $timeout) : int
    {
        $rc = ZOK;
        $started = new TimeVal();
        // we can't use dequeue_buffer() here because if (non-blocking) send_buffer()
        // returns EWOULDBLOCK we'd have to put the buffer back on the queue.
        // we use a recursive lock instead and only dequeue the buffer if a send was
        // successful
        $this->lock_buffer_list($this->to_send);
        while ($this->to_send->head && $this->state == ZOO_CONNECTED_STATE) {
            if($timeout!=0){
                $now = new TimeVal();
                $elapsed = $this->calculate_interval($started, $now);
                if ($elapsed > $timeout) {
                    $rc = ZOPERATIONTIMEOUT;
                    break;
                }
            }

            $rc = $this->send_buffer($this->fd, $this->to_send->head);
            if($rc==0 && $timeout==0){
                /* send_buffer would block while sending this buffer */
                $rc = ZOK;
                break;
            }
            if ($rc < 0) {
                $rc = ZCONNECTIONLOSS;
                break;
            }
            // if the buffer has been sent successfully, remove it from the queue
            if ($rc > 0)
                $this->to_send = new BufferHeadT();
            $this->last_send->getNow();
            $rc = ZOK;
        }
        $this->unlock_buffer_list($this->to_send);
        return $rc;
    }
    public function cleanup_bufs(int $callCompletion,int $rc)
    {
//        enter_critical(zh);
        $this->to_send = NULL;
        $this->to_process = NULL;
        $this->free_completions($callCompletion, $rc);
//        leave_critical(zh);
        if ($this->input_buffer && $this->input_buffer != $this->primer_buffer) {
            $this->input_buffer = NULL;
        }
    }
    public function send_ping() : int
    {
        $oa = new Oarchive();
        $h = new RequestHeader(PING_XID, ZOO_PING_OP);
        
        $rc = $h->serialize($oa, "header", $h);
        $this->enter_critical();
        $this->last_ping->getNow();
        if (!is_object($this->to_send)) {
            $this->to_send = new BufferHeadT();
        }
        var_dump($oa, pack("N", 8) . $oa->get_buffer());
        $rc = $rc < 0 ? $rc : $this->queue_buffer_bytes($this->to_send, pack("N", 8) . $oa->get_buffer(), $oa->get_buffer_len());
        $this->leave_critical();
        unset($oa);
       return $rc < 0 ? $rc : $this->adaptor_send_queue(0);
    }
    protected function send_buffer(Client $fd, BufferListT &$buff) : int
    {
        $len = $buff->len;
        $off = $buff->curr_offset;
        $rc = -1;

        if ($off < 4) {
            /* we need to send the length at the beginning */
//            int nlen = htonl(len);
//            char *b = (char*)&nlen;
//            $rc = zookeeper_send($fd, $len + $off, 4 - $off);
            $rc = $fd->send(chr($len+$off));
            if (!$rc) {
                if ($fd->errCode != EAGAIN) {
                    return -1;
                } else {
                    return 0;
                }
            } else {
                $buff->curr_offset  += $rc;
            }
            $off = $buff->curr_offset;
        }
        if ($off >= 4) {
            /* want off to now represent the offset into the buffer */
            $off -= 4;
//            rc = zookeeper_send(fd, buff->buffer + off, len - off);
            $rc = $fd->send(substr($buff->buffer, $off, $len));
            if (!$rc) {
                if ($fd->errCode != EAGAIN) {
                    return -1;
                }
            } else {
                $buff->curr_offset += $rc;
            }
        }
        return $buff->curr_offset == $len + 4;
    }
    public function calculate_interval(TimeVal $start, TimeVal $end) : int
    {
        $i = clone $end;
        $i->tv_sec -= $start->tv_sec;
        $i->tv_usec -= $start->tv_usec;
        $interval = $i->tv_sec * 1000 + ($i->tv_usec/1000);
        return $interval;
    }
    protected function adaptor_send_queue(int $timeout)
    {
        if(!$this->close_requested){
//            return wakeup_io_thread($zh);
        }
        // don't rely on the IO thread to send the messages if the app has
        // requested to close 
        return $this->flush_send_queue($timeout);
    }
    protected function assert($param)
    {
        try {
            if (!$param) {
                throw new \ErrorException($param);
            }
        } catch (\ErrorException $exc) {
            echo $exc->getTraceAsString();
            exit;
        }
    }
    protected function lock_completion_list(CompletionHeadT &$l = null) : bool
    {
        if (!$l || !is_object($l)) {
            return FALSE;
        }
        return $l->lock->lock();
    }
    protected function unlock_completion_list(CompletionHeadT &$l = null) : bool
    {
        if (!$l || !is_object($l)) {
            return FALSE;
        }
        return $l->lock->unlock();
    }
    protected function unlock_buffer_list(BufferHeadT &$l = null) : bool
    {
        if (!$l || !is_object($l)) {
            return FALSE;
        }
        return $l->lock->unlock();
    }
    
    protected function lock_buffer_list(BufferHeadT &$l = null) : bool
    {
        if (!$l || !is_object($l)) {
            return FALSE;
        }
        return $l->lock->lock();
    }
    private function queue_buffer(BufferHeadT &$list, BufferListT &$b, int $add_to_front)
    {
        $b->next = null;
        $this->lock_buffer_list($list);
        if ($list->head) {
            $this->assert($list->last);
            // The list is not empty
            if ($add_to_front) {
                $b->next = $list->head;
                $list->head = $b;
            } else {
                $list->last->next = $b;
                $list->last = $b;
            }
        }else{
            // The list is empty
            $this->assert(!$list->head);
            $list->head = $b;
            $list->last = $b;
        }
        $this->unlock_buffer_list($list);
    }
    public function checkResponseLatency()
    {
        $now = new TimeVal();

        if($this->socket_readable->tv_sec == 0)
            return;

        $delay = $this->calculate_interval($zh->socket_readable, $now);
        if($delay>20){
            Log::LOG_DEBUG("The following server response has spent at least $delay ms sitting in the client socket recv buffer", __LINE__, __METHOD__);
        }

        $this->socket_readable->tv_sec = $this->socket_readable->tv_usec=0;
    }
    

    public function is_unrecoverable() : int
    {
        return ($this->state < 0) ? ZINVALIDSTATE : ZOK;
    }
    protected function handle_error(int $rc)
    {
        $this->fd->close();
        if ($this->is_unrecoverable()) {
            Log::LOG_DEBUG("Calling a watcher for a ZOO_SESSION_EVENT and the state=%s", $this->state2String($this->state));
            $this->queue_session_event($this->state);
        } else if ($this->state == ZOO_CONNECTED_STATE) {
            LOG_DEBUG(("Calling a watcher for a ZOO_SESSION_EVENT and the state=CONNECTING_STATE"));
            $this->queue_session_event(ZOO_CONNECTING_STATE);
        }
        $this->cleanup_bufs(1, $rc);
        $this->fd = NULL;
        $this->connect_index++;
        if (!$this->is_unrecoverable()) {
            $this->state = 0;
        }
        if ($this->process_async($this->outstanding_sync)) {
            $this->process_completions();
        }
    }
    public function handle_socket_error_msg(int $line, int $rc,string $format, ...$arg) : int
    {
        if(Log::$logLevel >= ZOO_LOG_LEVEL_ERROR){
            Log::log_message(ZOO_LOG_LEVEL_ERROR, $line, __METHOD__,
                "Socket [" . $this->format_current_endpoint_info() . "] zk retcode=$rc, errno=" .
                    $this->fd->errCode . ": " . swoole_strerror($this->fd->errCode));
        }
        $this->handle_error($rc);
        return $rc;
    }
    protected function queue_session_event(int $state) : int
    {
        $rc = 0;
        $evt = new WatcherEvent(ZOO_SESSION_EVENT, $state, "");
        $hdr = new ReplyHeader(WATCHER_EVENT_XID, 0, 0);
        $oa = new Oarchive();

        $rc = $hdr->serialize($oa, "hdr", $hdr);
        $rc = $rc < 0 ? $rc : $evt->serialize($oa, "event", $evt);
        if($rc < 0){
            throw new ZookeeperException('out of memory');
        }
        $cptr = new CompletionListT(WATCHER_EVENT_XID);
        $cptr->buffer->buffer = $oa->get_buffer();
        $cptr->buffer->len = $oa->get_buffer_len();
        $cptr->buffer->curr_offset = $oa->get_buffer_len();
        /* We queued the buffer, so don't free it */
        unset($oa);
        $cptr->c->watcher_result = Hashtable::collectWatchers($this, ZOO_SESSION_EVENT, "");
        $this->queue_completion($this->completions_to_process, $cptr, 0);
        if ($this->process_async($this->outstanding_sync)) {
            $this->process_completions();
        }
        return ZOK;
    }
    public function process_completions()
    {
        echo __FILE__, ' ', __METHOD__, ' ', __LINE__, PHP_EOL;
    }
    public static function process_async(int $outstanding_sync = 0)
    {
        return 0;
    }
    protected function state2String(int $state)
    {
        switch($state){
        case 0:
            return "ZOO_CLOSED_STATE";
        case CONNECTING_STATE_DEF:
            return "ZOO_CONNECTING_STATE";
        case ASSOCIATING_STATE_DEF:
            return "ZOO_ASSOCIATING_STATE";
        case CONNECTED_STATE_DEF:
            return "ZOO_CONNECTED_STATE";
        case EXPIRED_SESSION_STATE_DEF:
            return "ZOO_EXPIRED_SESSION_STATE";
        case AUTH_FAILED_STATE_DEF:
            return "ZOO_AUTH_FAILED_STATE";
        }
        return "INVALID_STATE";
    }
    protected function queue_buffer_bytes(BufferHeadT &$list, string $buff, int $len) : int
    {
//        buffer_list_t *b  = allocate_buffer(buff,len);
        $b = new BufferListT($buff, $len);
        $this->queue_buffer($list, $b, 0);
        return ZOK;
    }
    protected function inc_ref_counter(int $i) : int
    {
        $this->ref_counter += ($i < 0 ? -1 : ($i > 0 ? 1 : 0));
        return $this->ref_counter;
    }
    protected function enter_critical() : int
    {
        return 0;
    }
    protected function leave_critical() : int
    {
        return 0;
    }
    protected function free_completions(int $callCompletion, int $reason)
    {
        $tmp_list = new CompletionHeadT();
        $oa = new Oarchive();
        $h = new ReplyHeader();
        $auth_completion = null;
        $a_list = new AuthCompletionListT();
        $a_tmp = new AuthCompletionListT();

        if ($this->sent_requests && $this->sent_requests->lock->lock()) {
            $tmp_list = $this->sent_requests;
            $this->sent_requests->head = new CompletionListT();
            $this->sent_requests->last = new CompletionListT();
            $this->sent_requests->lock->unlock();
        
            while ($tmp_list->head) {
		$cptr = new \Zkclient\Adaptor\CompletionListT();
                $cptr = &$tmp_list->head;
                $tmp_list->head = $cptr->next;
                if ($cptr->c->data_result == SYNCHRONOUS_MARKER) {
//                    struct sync_completion *sc = (struct sync_completion*)cptr->data;
                    $sc = new syncCompletion();
                    $sc = $cptr->data;
                    $sc->rc = $reason;
                    $this->notify_sync_completion($sc);
                    $this->outstanding_sync--;
                    unset($cptr);
                } else if ($callCompletion) {
                    // Fake the response
                    $bptr = new BufferListT();
                    $h->xid = $cptr->xid;
                    $h->zxid = -1;
                    $h->err = $reason;
                    $oa->create_buffer_oarchive();
                    $oa->serialize_ReplyHeader("header", $h);
                    $bptr->len = $oa->get_buffer_len();
                    $bptr->buffer = $oa->get_buffer();
                    unset($oa);
                    $cptr->buffer = $bptr;
                    $this->queue_completion($this->completions_to_process, $cptr, 0);
                }
            }
        }
        if ($this->auth_h && $this->auth_h->lock->lock()) {
            $a_list->completion = NULL;
            $a_list->next = NULL;
        
            $a_list->get_auth_completions($this->auth_h);
            $this->auth_h->lock->unlock();
	    $a_list->rewind();
            // chain call user's completion function
            while ($a_list->valid()) {
		$a_tmp = &$a_list->current();
		if (!$a_tmp->completion) {
		    $a_list->next();
		    continue;
		}
                $auth_completion = $a_tmp->completion;
                $auth_completion($reason, $a_tmp->auth_data);
                $a_list->next();
            }
        }
        unset($a_list);
    }
    protected function queue_completion(CompletionHeadT &$list, CompletionHeadT &$c, int $add_to_front)
    {
        $this->lock_completion_list($list);
        $this->queue_completion_nolock($list, $c, $add_to_front);
        $this->unlock_completion_list($list);
    }
    protected function queue_completion_nolock(CompletionHeadT &$list, CompletionHeadT &$c, int $add_to_front)
    {
        $c->next = null;
        if($list->last){
            if(!$add_to_front){
                $list->last->next = &$c;
                $list->last = &$c;
            }else{
                $list->head = &$c;
                $list->last = &$c;
            }
        }
    }
    protected function format_current_endpoint_info() : string
    {
        return self::format_endpoint_info($this->addrs[$this->connect_index]);
    }
    public function format_endpoint_info(string $ep) : string
    {
        if (!$ep) {
            return 'null';
        } else {
            return $ep;
        }
    }
    protected function get_xid() : int
    {
	self::$xid = -1;
	if (self::$xid == -1) {
	    self::$xid = time();
	}
	return fetch_and_add(self::$xid, 1);
    }
    private function fetch_and_add(int &$operand, int $incr) : int
    {
        return $operand += $incr;
    }
}

class TimeVal {
    /**
    * @var int
    */
    public $tv_sec; /* int Seconds. */
    /**
    * @var int
    */
    public $tv_usec; /* int Microseconds. */

    public function __construct() {
	$this->getNow();
    }

    public function getNow() {
	$timezone = date_default_timezone_get();
	$time = \microtime();
//        var_dump($time);
	$tv = \explode(' ', $time);
	$this->tv_sec = $tv[1];
	$this->tv_usec = intval($tv[0] * 1000);
//        var_dump($this);
	date_default_timezone_set($timezone);
    }
    public function __toString() {
        return $this->tv_sec * 1000 . $this->tv_usec / 1000;
    }
    public function get_timeval(int $interval) : TimeVal
    {
        if ($interval < 0) {
            $interval = 0;
        }
        $this->tv_sec = $interval / 1000;
        $this->tv_usec = ($interval % 1000) * 1000;
        return $this;
    }
}

class Utsname{
    public $sysname;
    public $nodename;
    public $release;
    public $version;
    public $machine;
    public function __construct(){
        $this->sysname = php_uname('s');
        $this->nodename = php_uname('n');
        $this->release = php_uname('r');
        $this->version = php_uname('v');
        $this->machine = php_uname('m');
    }
}
