<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Spool\Zookeeper;
require 'src/Base.php';

use Spool\Zookeeper\Lib\WatcherFn;
use Spool\Zookeeper\Lib\ClientIdT;
use Spool\Zookeeper\Lib\ZhandleT;
use Spool\Zookeeper\Lib\Log;
use Spool\Zookeeper\Lib\Utsname;
use Spool\Zookeeper\Lib\NullWatcherFn;
use Spool\Zookeeper\Adaptor\AuthListHead;
use Spool\Zookeeper\Adaptor\BufferListT;
use Spool\Zookeeper\Adaptor\BufferHeadT;
use Spool\Zookeeper\Lib\timeVal;
//use Spool\Zookeeper\Zoo\CompletionHeadT;
use Spool\Zookeeper\Adaptor\PrimeStruct;
use Spool\Zookeeper\Hashtable\WatcherObjectList;
use Swoole\Client;

/**
 * Zookeeper client for php
 *
 * @author 大天使长
 */
class Zookeeper {
    const PACKAGE_STRING = "zookeeper PHP client 3.4.11 Base 0.0.1";
    /**
     * @var Spool\Zookeeper\Lib\ZhandleT 
     */
    protected $zh;
    /**
     * 类初始化
     * @param string $host
     * @param WatcherFn $watcher
     * @param int $recvTimeout
     * @param ClientIdT $clientId
     */
    public function __construct(string $host, WatcherFn $watcher = null, int $recvTimeout = 30000, ClientIdT $clientId = null)
    {
        $this->zh = new ZhandleT();
        $rc = $this->init($host, $watcher, $recvTimeout, $clientId);
    }
    /**
     * zookeeper服务初始化
     * @param string $host
     * @param WatcherFn $watcher
     * @param int $recv_timeout
     * @param \Spool\Zookeeper\ClientIdT $clientid
     * @param type $context 保留
     * @param int $flags = 0 保留
     */
    public function init(string $host, WatcherFn $watcher = null, int $recv_timeout = 30000, ClientIdT $clientid = null, $context = null, int $flags = 0) : int
    {
        $errnosave = 0;
        $index_chroot = '';
        $this->logEnv();
        $client_id = $clientid ? $clientid->client_id : 0;
        $client_passwd = $clientid && !$clientid->passwd ? "<hidden>" : "<null>";
        Log::LOG_INFO("Initiating client connection, host=$host sessionTimeout=$recv_timeout watcher=$watcher sessionId=$client_id sessionPasswd=$client_passwd context=$context flags=$flags", __LINE__, __FUNCTION__);
        $zh = &$this->zh;
        $zh->fd = new Client(SWOOLE_SOCK_TCP);
        $zh->state = NOTCONNECTED_STATE_DEF;
        $zh->context = $context;
        $zh->recv_timeout = $recv_timeout;
//        $this->initAuthInfo($zh->auth_h);
        if(is_callable($watcher)){
            $zh->watcher = $watcher;
        }else{
            $zh->watcher = new NullWatcherFn();
        }
        if(!$host){
            throw new ZookeeperException('host is empty');
        }
        $index_chroot = strchr($host, '/');
        if($index_chroot){
            $zh->chroot = $index_chroot;
            $index_chroot_len = strlen($index_chroot);
            if($index_chroot_len === 1){
                $zh->chroot = '';
            }
            $zh->hostname = substr($host, 0, strlen($host) - $index_chroot_len);
        }else{
            $zh->chroot = '';
            $zh->hostname = $host;
        }
        if($zh->chroot && !self::isValidPath($zh->chroot, 0)) {
            throw new ZookeeperException('chroot is error');
        }
        if(!$zh->hostname){
            throw new ZookeeperException('hostname is empty');
        }
        $this->getaddrs($zh);
        
        $zh->connect_index = 0;
        if ($clientid) {
            $zh->client_id = $clientid;
        } else {
            $zh->client_id = null;
        }
        $zh->primer_buffer = new BufferListT();
        $zh->primer_buffer->buffer = $zh->primer_storage_buffer;
        $zh->primer_buffer->curr_offset = 0;
        $zh->primer_buffer->len = strlen($zh->primer_storage_buffer);
        $zh->primer_buffer->next = 0;
        $zh->last_zxid = 0;
        $zh->next_deadline = new timeVal();
        $zh->next_deadline->tv_sec = $zh->next_deadline->tv_usec = 0;
        $zh->socket_readable = new timeVal();
        $zh->socket_readable->tv_sec = $zh->socket_readable->tv_usec = 0;
        $zh->active_node_watchers = [];
        $zh->active_exist_watchers = [];
        $zh->active_child_watchers = [];
        if ($this->adaptor_init($zh) == -1) {
            throw new ZookeeperException('start init is error');
        }
        return $this->zh;
    }
    
    protected function logEnv()
    {
//        $buf = '';
        $utsname = new Utsname();

        Log::LOG_INFO(sprintf("Client environment:zookeeper.version=%s", self::PACKAGE_STRING), __LINE__, __FUNCTION__);

        $buf = getenv('HOSTNAME') ?: '';
        if($buf){
        Log::LOG_INFO(sprintf("Client environment:host.name=%s", $buf), __LINE__, __FUNCTION__);
        }else{
        Log::LOG_INFO(sprintf("Client environment:host.name=<not implemented>"), __LINE__, __FUNCTION__);
        }

        if($utsname->sysname){
        Log::LOG_INFO(sprintf("Client environment:os.name=%s", $utsname->sysname), __LINE__, __FUNCTION__);
        }else{
        Log::LOG_INFO(sprintf("Client environment:os.name=<not implemented>"), __LINE__, __FUNCTION__);
        }
        if($utsname->release){
        Log::LOG_INFO(sprintf("Client environment:os.arch=%s", $utsname->release), __LINE__, __FUNCTION__);
        }else{
        Log::LOG_INFO(sprintf("Client environment:os.arch=<not implemented>"), __LINE__, __FUNCTION__);
        }
        if($utsname->version){
        Log::LOG_INFO(sprintf("Client environment:os.version=%s", $utsname->version), __LINE__, __FUNCTION__);
        }else{
        Log::LOG_INFO(sprintf("Client environment:os.version=<not implemented>"), __LINE__, __FUNCTION__);
        }

        if($username = getenv('USER')){
        Log::LOG_INFO(sprintf("Client environment:user.name=%s", $username), __LINE__, __FUNCTION__);
        }else{
        Log::LOG_INFO(sprintf("Client environment:user.name=<not implemented>"), __LINE__, __FUNCTION__);
        }

        if ($buf = getenv('HOME')) {
        Log::LOG_INFO(sprintf("Client environment:user.home=%s", $buf), __LINE__, __FUNCTION__);
        } else {
        Log::LOG_INFO(sprintf("Client environment:user.home=<NA>"), __LINE__, __FUNCTION__);
        }

        if ($buf = getenv('PWD')) {
        Log::LOG_INFO(sprintf("Client environment:user.dir=%s", $buf), __LINE__, __FUNCTION__);
        } else {
        Log::LOG_INFO(sprintf("Client environment:user.dir=<not implemented>"), __LINE__, __FUNCTION__);
        }
    }
    protected function getaddrs(ZhandleT &$zh) : bool
    {
        $hosts = $zh->hostname;
        $zh->addrs_count = 0;
        $zh->addrs = [];
        $host = explode(',', $hosts);
        foreach($host as $value){
            $port_spec = explode(':', $value);
            if(count($port_spec) < 2){
                Log::LOG_ERROR("no port in $hosts", __LINE__, __FUNCTION__);
                throw new ZookeeperException('port is empty');
            }
            $port = $port_spec[1];
            if(!$port || !is_numeric($port)){
                Log::LOG_ERROR("invalid port in $hosts", __LINE__, __FUNCTION__);
                throw new ZookeeperException('port is invalid');
            }
            $addrs = gethostbynamel($port_spec[0]);
            if(!$addrs){
                Log::LOG_ERROR("getaddrinfo is empty\n", __LINE__, __FUNCTION__);
                throw new ZookeeperException('socket_addrinfo_lookup returns an empty');
            }
            $zh->addrs_count += count($addrs);
            foreach($addrs as $val){
                $zh->addrs[] = $val . ':' . $port;
            }
        }
        return true;
    }
}
