<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Spool\Zookeeper\Lib;

use Spool\Zookeeper\Lib\ZhandleT;
use Spool\Zookeeper\Lib\Log;
use Spool\Zookeeper\Adaptor\ConnectReq;
use Spool\Zookeeper\Adaptor\BufferListT;
use Swoole\Client;
use Swoole\Timer;

/**
 * Description of adaptor
 *
 * @author 大天使长
 */
class Adaptor {
    /**
     * @var ZhandleT
     */
    protected $zh;
    public static $send_to;
    public function init(ZhandleT &$zh) : int
    {
        $this->zh = &$zh;
        $zh->api_prolog();
        Log::LOG_DEBUG("starting threads...", __LINE__, __METHOD__);
        $this->do_io($zh);
        $zh->api_epilog(1);
        return 0;
    }
    protected function do_io(ZhandleT &$zh) {
        $fd = $zh->fd;
        $fd->on("connect", [$this, 'on_connect']);
        $fd->on("receive", [$this, 'on_recv']);
        $fd->on("error", [$this, 'on_error']);
        $fd->on("close", [$this, 'on_close']);
        $this->zookeeper_interest($zh);
        $interest = ZOOKEEPER_READ;
    }
    public function on_connect(Client $fd) {
        $zh = &$this->zh;
        $host = explode(':', $zh->addrs[$zh->connect_index]);
        $hostname = $host[0];
        $port = $host[1];
        if(($rc = $this->prime_connection($zh)) != 0)
            return $zh->api_epilog($rc);
        Log::LOG_INFO("Initiated connection to server [$hostname:$port]", __LINE__, __FUNCTION__);
        $zh->state = ZOO_CONNECTED_STATE;
        $send_to = (string)$zh->recv_timeout/3;
        self::$send_to = Timer::after($send_to / 100, [$this, 'zookeeper_interest'], $zh);
        echo $send_to, ' ', $send_to / 100, ' ', self::$send_to, "\n";
    }
    public function on_recv(Client $fd, string $data) {
        echo "server recv: ";
        var_dump($data);
    }
    public function on_close(Client $fd) {
        if (!$this->zh->close_requested) {
            $this->zookeeper_interest($this->zh);
        } else {
            $this->zh->close();
        }
    }
    public function on_error(Client $fd) {
        
    }
    public function zookeeper_interest(ZhandleT &$zh, Client $fd = null, int $interest = 0, TimeVal $tv = null) : int
    {
        $now = new TimeVal();
//        var_dump($now);
        $tv = $tv ?: new TimeVal();
        if($zh->next_deadline->tv_sec!=0 || $zh->next_deadline->tv_usec!=0){
            $time_left = $zh->calculate_interval($zh->next_deadline, $now);
//        var_dump($now);
            if ($time_left > 10)
                Log::LOG_WARN("Exceeded deadline by $time_left ms", __LINE__, __FUNCTION__);
        }
        $zh->api_prolog();
        $tv->tv_sec = 0;
        $tv->tv_usec = 0;
        if(!$zh->fd->isConnected()){
            if($zh->connect_index == $zh->addrs_count){
                $zh->connect_index = 0;
                    //sleep 50ms
                go(function () {
                    usleep(1000 * 50);
                });
            }else{
                $rc = 0;
                $enable_tcp_nodelay = 1;
//                var_dump($zh->addrs, $zh->connect_index);
                $host = explode(':', $zh->addrs[$zh->connect_index]);
                $hostname = $host[0];
                $port = $host[1];
                $rc = $zh->fd->connect($hostname, $port, 0.5);
                if (!$rc) {
                    /* we are handling the non-blocking connect according to
                     * the description in section 16.3 "Non-blocking connect"
                     * in UNIX Network Programming vol 1, 3rd edition */
                    if ($zh->fd->errCode == EWOULDBLOCK || $zh->fd->errCode == EINPROGRESS){
                        $zh->state = ZOO_CONNECTING_STATE;
                    } else {
                        return $zh->api_epilog($this->handle_socket_error_msg($zh, __LINE__, __FUNCTION__, ZCONNECTIONLOSS, $zh->fd->errCode, "connect() call failed"));
                    }
                } else {
                    //此处处理挪到了on_connect里面
                }
            }
            $fd = &$zh->fd;
            $tv->get_timeval((string)$zh->recv_timeout/3);
            $zh->last_recv = $now;
            $zh->last_send = $now;
            $zh->last_ping = $now;
        }
        
        if($zh->fd->isConnected()) {
            $idle_recv = $zh->calculate_interval($zh->last_recv, $now);
            $idle_send = $zh->calculate_interval($zh->last_send, $now);
            $recv_to   = (string)$zh->recv_timeout * 2 / 3 - $idle_recv;
            $send_to   = (string)$zh->recv_timeout / 3;
            // have we exceeded the receive timeout threshold
            if ($recv_to <= 0) {
                // We gotta cut our losses and connect to someone else
                $errno = ETIMEDOUT;
                
                $interest=0;
                $tv->get_timeval(0);
                return $zh->api_epilog($zh->handle_socket_error_msg(
                        __LINE__,ZOPERATIONTIMEOUT,
                        "connection to %s timed out (exceeded timeout by %dms)",
                        $zh->format_endpoint_info($zh->addrs[$zh->connect_index]),
                        -$recv_to));
            }
//            echo __CLASS__, ' ', __METHOD__, ' ', __LINE__, "\n";
//            var_dump($zh->state, ZOO_CONNECTED_STATE);
            // We only allow 1/3 of our timeout time to expire before sending
            // a PING
            if ($zh->state == ZOO_CONNECTED_STATE) {
                $send_to = (string)$zh->recv_timeout/3 - $idle_send;
                echo __CLASS__, ' ', __METHOD__, ' ', __LINE__, "send_to: $send_to", "\n";
                if ($send_to <= 1000) {
                    if (!is_object($zh->sent_requests) || !is_object($zh->sent_requests->head) || !$zh->sent_requests->head) {
    //                    LOG_DEBUG(("Sending PING to %s (exceeded idle by %dms)",
    //                                    format_current_endpoint_info(zh),-send_to));
                        $rc = $zh->send_ping();
                        if ($rc < 0){
                            Log::LOG_ERROR("failed to send PING request (zk retcode=$rc)", __LINE__, __METHOD__);
                            return $zh->api_epilog($rc);
                        }
                    }
                    $send_to = (string)$zh->recv_timeout / 3;
                    echo __CLASS__, ' ', __METHOD__, ' ', __LINE__, "reset send_to: $send_to", "\n";
                }
                if (self::$send_to) {
                    Timer::clear(self::$send_to);
                }
//                var_dump($zh->recv_timeout);
                self::$send_to = Timer::after($send_to / 100, [$this, 'zookeeper_interest'], $zh);
            }
            // choose the lesser value as the timeout
            $tv->get_timeval($recv_to < $send_to ? $recv_to : $send_to);
            $zh->next_deadline->tv_sec = $now->tv_sec + $tv->tv_sec;
            $zh->next_deadline->tv_usec = $now->tv_usec + $tv->tv_usec;
            if ($zh->next_deadline->tv_usec > 1000000) {
                $zh->next_deadline->tv_sec += $zh->next_deadline->tv_usec / 1000000;
                $zh->next_deadline->tv_usec = $zh->next_deadline->tv_usec % 1000000;
            }
            $interest = ZOOKEEPER_READ;
            /* we are interested in a write if we are connected and have something
             * to send, or we are waiting for a connect to finish. */
            if ($zh->to_send && $zh->to_send->head && ($zh->state == ZOO_CONNECTED_STATE)
            || $zh->state == ZOO_CONNECTING_STATE) {
                $interest |= ZOOKEEPER_WRITE;
            }
        }
        return $zh->api_epilog(ZOK);
    }
    protected function prime_connection(ZhandleT &$zh) : int
    {
        /*this is the size of buffer to serialize req into*/
        //HANDSHAKE_REQ_SIZE
        $len = HANDSHAKE_REQ_SIZE;
        $req = new ConnectReq();
        $req->protocolVersion = 0;
        $req->sessionId = $zh->client_id->clientId;
        $req->passwd_len = 16;
        $req->passwd = $zh->client_id->passwd;
        $req->timeOut = (string)$zh->recv_timeout;
        $req->lastZxidSeen = $zh->last_zxid;
        $hlen = pack('N', $len);
        /* We are running fast and loose here, but this string should fit in the initial buffer! */
        $rc = $zh->fd->send($hlen);
//        serialize_prime_connect(&req, buffer_req);
//        rc=rc<0 ? rc : zookeeper_send(zh->fd, buffer_req, len);
//        var_dump($req);exit;
        $buffer_req = (string)$req;
        $rc = $rc < 0 ? $rc : $zh->fd->send($buffer_req);
        if ($rc < 0) {
            return $this->handle_socket_error_msg($zh, __LINE__, ZCONNECTIONLOSS,
                    "failed to send a handshake packet: " . swoole_strerror($zh->fd->errCode));
        }
        $zh->state = ZOO_ASSOCIATING_STATE;

        $zh->input_buffer = &$zh->primer_buffer;
        /* This seems a bit weird to to set the offset to 4, but we already have a
         * length, so we skip reading the length (and allocating the buffer) by
         * saying that we are already at offset 4 */
        $zh->input_buffer->curr_offset = 4;

        return ZOK;
    }
    protected function handle_socket_error_msg(ZhandleT $zh, int $line, string $funcName, int $rc, int $errno, string ...$format) : int
    {
        if(Log::$logLevel >= ZOO_LOG_LEVEL_ERROR){
            $socket = $zh->addrs[$zh->connect_index];
            $errstr = swoole_strerror($errno);
            Log::log_message(ZOO_LOG_LEVEL_ERROR, $line, $funcName, "Socket [$socket] zk retcode=$rc, errno=$errno($errstr): $format");
        }
        self::handle_error($zh, $rc);
        return $rc;
    }
    protected function handle_error(ZhandleT &$zh,int $rc)
    {
        $zh->fd->close();
        if ($this->is_unrecoverable($zh)) {
            $errstr = self::state2String($zh->state);
            Log::LOG_DEBUG("Calling a watcher for a ZOO_SESSION_EVENT and the state=$errstr", __LINE__, __FUNCTION__);
            self::queue_session_event($zh, $zh->state);
        } else if ($zh->state == ZOO_CONNECTED_STATE) {
            Log::LOG_DEBUG("Calling a watcher for a ZOO_SESSION_EVENT and the state=CONNECTING_STATE", __LINE__, __FUNCTION__);
            self::queue_session_event($zh, ZOO_CONNECTING_STATE);
        }
        //tagstop
        $zh->cleanup_bufs(1, $rc);
        $zh->fd = NULL;
        $zh->connect_index++;
        if (!$this->is_unrecoverable($zh)) {
            $zh->state = 0;
        }
        /* 下面不执行
        if (process_async($zh->outstanding_sync)) {
            process_completions($zh);
        }
         */
    }
}
