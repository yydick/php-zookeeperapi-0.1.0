<?php

namespace Spool\Zookeeper\Adaptor;

use Spool\Zookeeper\Adaptor\AuthInfo;

class AuthListHead
{
    /**
    * @var AuthInfo
    */
    public $auth;
    /**
    * @var \SyncMutex
    */
    public $lock;
}
