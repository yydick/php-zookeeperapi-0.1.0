<?php

namespace Spool\Zookeeper\Hashtable;

use Spool\Zookeeper\Zoo\WatcherFn;

class WatcherObjectT
{
    /**
    * @var WatcherFn
    */
    public $watcher;
    /**
    * @var mixed
    */
    public $context;
    /**
    * @var WatcherObjectT
    */
    public $next;
}