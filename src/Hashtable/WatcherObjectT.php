<?php

namespace Zkclient\Hashtable;

use Zkclient\Zoo\WatcherFn;

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