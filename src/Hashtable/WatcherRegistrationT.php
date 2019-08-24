<?php

namespace Spool\Zookeeper\Hashtable;

use Spool\Zookeeper\Zoo\WatcherFn;
use Spool\Zookeeper\Hashtable\ResultCheckerFn;


class WatcherRegistrationT
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
    * @var ResultCheckerFn
    */
    public $checker;
    /**
    * @var string
    */
    public $path;
}