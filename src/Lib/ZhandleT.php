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
     * @var int
     */
    public $last_recv;		    /* The time that the last message was received */
    /**
     * @var int
     */
    public $last_send;		    /* The time that the last message was sent */
    /**
     * @var int
     */
    public $last_ping;		    /* The time that the last PING was sent */
    /**
     * @var int
     */
    public $next_deadline;	    /* The time of the next deadline */
    /**
     * @var int
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
     * @var int
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
    protected static $xid;
    protected static $send_to;
    /**
     * 初始化
     * @param string $host
     * @param \Spool\Zookeeper\Lib\WatcherFn $watcher
     * @param int $recv_timeout
     * @param ClientIdT $clientid
     * @param type $context
     * @param int $flags
     * @throws ZookeeperException
     */
    public function __construct(string $host, WatcherFn $watcher = null, int $recv_timeout = 30000, ClientIdT $clientid = null, $context = null, int $flags = 0) {
        $errnosave = 0;
        $index_chroot = '';
        $this->logEnv();
        $client_id = $clientid ? $clientid->client_id : 0;
        $client_passwd = $clientid && !$clientid->passwd ? "<hidden>" : "<null>";
        Log::LOG_INFO("Initiating client connection, host=$host sessionTimeout=$recv_timeout watcher=$watcher sessionId=$client_id sessionPasswd=$client_passwd context=$context flags=$flags", __LINE__, __FUNCTION__);
        //Use Swoole async model
        $this->fd = new Client(SWOOLE_SOCK_TCP, SWOOLE_SOCK_ASYNC);
        $this->state = NOTCONNECTED_STATE_DEF;
        $this->context = $context;
        $this->recv_timeout = $recv_timeout;
//        $this->initAuthInfo($this->auth_h);
        if(is_callable($watcher)){
            $this->watcher = $watcher;
        }else{
            $this->watcher = new NullWatcherFn();
        }
        if(!$host){
            throw new ZookeeperException('host is empty');
        }
        $index_chroot = strchr($host, '/');
        if($index_chroot){
            $this->chroot = $index_chroot;
            $index_chroot_len = strlen($index_chroot);
            if($index_chroot_len === 1){
                $this->chroot = '';
            }
            $this->hostname = substr($host, 0, strlen($host) - $index_chroot_len);
        }else{
            $this->chroot = '';
            $this->hostname = $host;
        }
        if($this->chroot && !self::isValidPath($this->chroot, 0)) {
            throw new ZookeeperException('chroot is error');
        }
        if(!$this->hostname){
            throw new ZookeeperException('hostname is empty');
        }
        $this->getaddrs($this);
        
        $this->connect_index = 0;
        if ($clientid) {
            $this->client_id = $clientid;
        } else {
            $this->client_id = new ClientIdT();
        }
        $now = time();
        $this->primer_buffer = new BufferListT();
        $this->primer_buffer->buffer = $this->primer_storage_buffer;
        $this->primer_buffer->curr_offset = 0;
        $this->primer_buffer->len = strlen($this->primer_storage_buffer);
        $this->primer_buffer->next = 0;
        $this->last_zxid = 0;
        $this->next_deadline = $now;
        $this->socket_readable = $now;
        $this->active_node_watchers = [];
        $this->active_exist_watchers = [];
        $this->active_child_watchers = [];
        $this->api_prolog();
        Log::LOG_DEBUG("starting threads...", __LINE__, __METHOD__);
        $this->do_io($this);
        $this->api_epilog(1);
    }
    protected function do_io(ZhandleT &$this) {
        $fd = $this->fd;
        $fd->on("connect", [$this, 'on_connect']);
        $fd->on("receive", [$this, 'on_recv']);
        $fd->on("error", [$this, 'on_error']);
        $fd->on("close", [$this, 'on_close']);
        $this->zookeeper_interest($this);
        $interest = ZOOKEEPER_READ;
    }
    public final function on_connect(Client $fd) {
        $host = explode(':', $this->addrs[$this->connect_index]);
        $hostname = $host[0];
        $port = $host[1];
        if(($rc = $this->prime_connection($this)) != 0)
            return $this->api_epilog($rc);
        Log::LOG_INFO("Initiated connection to server [$hostname:$port]", __LINE__, __FUNCTION__);
        $this->state = ZOO_CONNECTED_STATE;
        $send_to = $this->recv_timeout/3;
        self::$send_to = Timer::after($send_to / 100, [$this, 'zookeeper_interest']);
        echo $send_to, ' ', $send_to / 100, ' ', self::$send_to, "\n";
    }
    public final function on_recv(Client $fd, string $data) {
        echo "server recv: ";
        var_dump($data);
    }
    public final function on_close(Client $fd) {
        if (!$this->zh->close_requested) {
            $this->zookeeper_interest();
        } else {
            $this->zh->close();
        }
    }
    public final function on_error(Client $fd) {
        
    }
    private function zookeeper_interest(Client $fd = null, int $interest = 0, int $tv = 0) : int
    {
        $now = time();
//        var_dump($now);
        if($this->next_deadline){
            $time_left = $now - $this->next_deadline;
            if ($time_left > 10){
                Log::LOG_WARN("Exceeded deadline by $time_left ms", __LINE__, __FUNCTION__);
            }
        }
        $this->api_prolog();
        if(!$this->fd->isConnected()){
            if($this->connect_index == $this->addrs_count){
                $this->connect_index = 0;
                    //sleep 50ms
                go(function () {
                    usleep(1000 * 50);
                });
            }else{
                $rc = 0;
                $enable_tcp_nodelay = 1;
//                var_dump($this->addrs, $this->connect_index);
                $host = explode(':', $this->addrs[$this->connect_index]);
                $hostname = $host[0];
                $port = $host[1];
                $rc = $this->fd->connect($hostname, $port, 0.5);
                if (!$rc) {
                    /* we are handling the non-blocking connect according to
                     * the description in section 16.3 "Non-blocking connect"
                     * in UNIX Network Programming vol 1, 3rd edition */
                    if ($this->fd->errCode == EWOULDBLOCK || $this->fd->errCode == EINPROGRESS){
                        $this->state = ZOO_CONNECTING_STATE;
                    } else {
                        return $this->api_epilog($this->handle_socket_error_msg($this, __LINE__, __FUNCTION__, ZCONNECTIONLOSS, $this->fd->errCode, "connect() call failed"));
                    }
                } else {
                    //此处处理挪到了on_connect里面
                }
            }
            $tv = $this->recv_timeout / 3;
            $this->last_recv = $now;
            $this->last_send = $now;
            $this->last_ping = $now;
        }
        
        if($this->fd->isConnected()) {
            $idle_recv = $now - $this->last_recv;
            $idle_send = $now - $this->last_send;
            $recv_to   = $this->recv_timeout * 2 / 3 - $idle_recv;
            $send_to   = $this->recv_timeout / 3;
            // have we exceeded the receive timeout threshold
            if ($recv_to <= 0) {
                // We gotta cut our losses and connect to someone else
                $errno = ETIMEDOUT;
                
                $interest=0;
                $tv = 0;
                return $this->api_epilog($this->handle_socket_error_msg(
                        __LINE__,ZOPERATIONTIMEOUT,
                        "connection to %s timed out (exceeded timeout by %dms)",
                        $this->format_endpoint_info($this->addrs[$this->connect_index]),
                        -$recv_to));
            }
//            echo __CLASS__, ' ', __METHOD__, ' ', __LINE__, "\n";
//            var_dump($this->state, ZOO_CONNECTED_STATE);
            // We only allow 1/3 of our timeout time to expire before sending
            // a PING
            if ($this->state == ZOO_CONNECTED_STATE) {
                $send_to = $this->recv_timeout/3 - $idle_send;
                echo __CLASS__, ' ', __METHOD__, ' ', __LINE__, "send_to: $send_to", "\n";
                if ($send_to <= 1) {
                    if (!is_object($this->sent_requests) || !is_object($this->sent_requests->head) || !$this->sent_requests->head) {
    //                    LOG_DEBUG(("Sending PING to %s (exceeded idle by %dms)",
    //                                    format_current_endpoint_info(zh),-send_to));
                        $rc = $this->send_ping();
                        if ($rc < 0){
                            Log::LOG_ERROR("failed to send PING request (zk retcode=$rc)", __LINE__, __METHOD__);
                            return $this->api_epilog($rc);
                        }
                    }
                    $send_to = $this->recv_timeout / 3;
                    echo __CLASS__, ' ', __METHOD__, ' ', __LINE__, "reset send_to: $send_to", "\n";
                }
                if (self::$send_to) {
                    Timer::clear(self::$send_to);
                }
//                var_dump($this->recv_timeout);
                self::$send_to = Timer::after($send_to, [$this, 'zookeeper_interest'], $this);
            }
            // choose the lesser value as the timeout
            $tv = $recv_to < $send_to ? $recv_to : $send_to;
            $this->next_deadline = $now;
            $interest = ZOOKEEPER_READ;
            /* we are interested in a write if we are connected and have something
             * to send, or we are waiting for a connect to finish. */
            if ($this->to_send && $this->to_send->head && ($this->state == ZOO_CONNECTED_STATE)
            || $this->state == ZOO_CONNECTING_STATE) {
                $interest |= ZOOKEEPER_WRITE;
            }
        }
        return $this->api_epilog(ZOK);
    }
    protected function handle_socket_error_msg(int $line, string $funcName, int $rc, int $errno, string ...$format) : int
    {
        if(Log::$logLevel >= ZOO_LOG_LEVEL_ERROR){
            $socket = $this->addrs[$this->connect_index];
            $errstr = swoole_strerror($errno);
            Log::log_message(ZOO_LOG_LEVEL_ERROR, $line, $funcName, "Socket [$socket] zk retcode=$rc, errno=$errno($errstr): $format");
        }
        self::handle_error($rc);
        return $rc;
    }
    protected static function handle_error(int $rc)
    {
        $this->fd->close();
        if ($this->is_unrecoverable()) {
            $errstr = self::state2String($this->state);
            Log::LOG_DEBUG("Calling a watcher for a ZOO_SESSION_EVENT and the state=$errstr", __LINE__, __FUNCTION__);
            self::queue_session_event($this, $this->state);
        } else if ($this->state == ZOO_CONNECTED_STATE) {
            Log::LOG_DEBUG("Calling a watcher for a ZOO_SESSION_EVENT and the state=CONNECTING_STATE", __LINE__, __FUNCTION__);
            self::queue_session_event($this, ZOO_CONNECTING_STATE);
        }
        //tagstop
        $this->cleanup_bufs(1, $rc);
        $this->fd = NULL;
        $this->connect_index++;
        if (!$this->is_unrecoverable($this)) {
            $this->state = 0;
        }
        /* 下面不执行
        if (process_async($this->outstanding_sync)) {
            process_completions($this);
        }
         */
    }
    protected function state2String(int $state) : string
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
