<?php

namespace Zkclient\Hashtable;

use Zkclient\Zoo\WatcherFn;
use Zkclient\Hashtable\ResultCheckerFn;


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