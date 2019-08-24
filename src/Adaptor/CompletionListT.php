<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Spool\Zookeeper\Adaptor;

use Spool\Zookeeper\Adaptor\BufferListT;
use Spool\Zookeeper\Zoo\CompletionT;
use Spool\Zookeeper\Hashtable\WatcherRegistrationT;

/**
 * Description of CompletionListT
 *
 * @author 陈浩波
 */
class CompletionListT {
    /**
    * @var int
    */
    public $xid;		    //int32_t
    /**
    * @var {\Spool\Zookeeper\Zoo\CompletionT|CompletionT}
    */
    public $c;			    //CompletionT
    /**
    * @var mixed
    */
    public $data;		    //void
    /**
    * @var {BufferListT|BufferListT}
    */
    public $buffer;		    //obj BufferListT
    /**
    * @var CompletionListT
    */
    public $next;		    //obj CompletionListT
    /**
    * @var WatcherRegistrationT
    */
    public $watcher;		//obj WatcherRegistrationT
}
