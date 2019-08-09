<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Zkclient\Adaptor;

/**
 * Description of ConnectReq
 *
 * @author 陈浩波
 */
class ConnectReq {
    /**
    * @var int
    */
    public $protocolVersion;	//int32T
    /**
    * @var int
    */
    public $lastZxidSeen;	//int64T
    /**
    * @var int
    */
    public $timeOut;		//int32_T
    /**
    * @var int
    */
    public $sessionId;		//int64T
    /**
    * @var int
    */
    public $passwd_len;		//int32_t
    /**
    * @var string
    */
    public $passwd;		//char[16]
}
