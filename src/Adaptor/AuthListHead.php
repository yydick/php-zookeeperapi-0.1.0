<?php

namespace Spool\Zookeeper\Adaptor;

use Spool\Zookeeper\Adaptor\AuthInfo;
use Swoole\Lock;

class AuthListHead
{
    /**
    * @var AuthInfo
    */
    public $auth;
    /**
    * @var Lock
    */
    public $lock;
}
