<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Spool\Zookeeper\Adaptor;

use Spool\Zookeeper\Classes\Buffer;
use Spool\Zookeeper\Adaptor\VoidCompletionT;

/**
 * Description of AuthInfo
 *
 * @author 陈浩波
 */
class AuthInfo extends \SplQueue
{
    /**
    * @var int
    */
    public $state;	    /* 0=>inactive, >0 => active */
    /**
    * @var string
    */
    public $scheme;	    //string
    /**
    * @var Buffer
    */
    public $auth;	    //obj buffer
    /**
    * @var VoidCompletionT
    */
    public $completion;	    //obj void_completion_t
    /**
    * @var string
    */
    public $data;	    //string
    /**
    * @var AuthInfo
    */
    public $next;	    //obj AuthInfo
}
