<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Spool\Zookeeper\Lib;

use Spool\Zookeeper\Adaptor\AuthListHead;
use Spool\Zookeeper\Adaptor\BufferListT;
use Spool\Zookeeper\Adaptor\BufferHeadT;
use Spool\Zookeeper\Zoo\CompletionHeadT;
use Spool\Zookeeper\Zoo\ClientIdT;
use Spool\Zookeeper\Adaptor\PrimeStruct;
use Spool\Zookeeper\Hashtable\WatcherObjectList;

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
    public $outstanding_sync;	    /* Number of outstanding synchronous requests */
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
}

class timeVal {
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
	$tv = \explode(' ', $time);
	$this->tv_sec = $tv[1];
	$this->tv_usec = $tv[0] * 1000000;
	date_default_timezone_set($timezone);
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
