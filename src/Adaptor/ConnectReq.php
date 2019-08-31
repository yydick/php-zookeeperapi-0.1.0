<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Spool\Zookeeper\Adaptor;

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
    public function __toString() : string
    {
        return $this->toString();
    }
    public function getLen() : int
    {
        $result = strlen($this);
        return $result;
    }
    public function __sleep() : string
    {
        return $this->toString();
    }
    protected function toString() : string
    {
        $result = pack("NJNJN", $this->protocolVersion, $this->lastZxidSeen, $this->timeOut, $this->sessionId, $this->passwd_len);
        for($i = 0; $i < 16; $i++){
            $result .= isset($this->passwd[$i]) ? ord($this->passwd[$i]) : chr(0);
        }
//        var_dump($result);
        return (string)$result;
    }
}
