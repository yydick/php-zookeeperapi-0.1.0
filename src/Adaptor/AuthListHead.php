<?php

namespace Zkclient\Adaptor;

use Zkclient\Adaptor\AuthInfo;

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
