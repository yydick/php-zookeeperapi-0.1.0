<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Spool\Zookeeper\Generated;

use Spool\Zookeeper\Classes\Oarchive;
use Spool\Zookeeper\Classes\Iarchive;
use Spool\Zookeeper\Classes\Buffer;

/**
 * Description of ConnectRequest
 *
 * @author 陈浩波
 */
class ConnectRequest {
    /**
    * @var int
    */
    public $protocolVersion;	    //int32_t
    /**
    * @var int
    */
    public $lastZxidSeen;	    //int64_t
    /**
    * @var int
    */
    public $timeOut;		    //int32_t
    /**
    * @var int
    */
    public $sessionId;		    //int64_t
    /**
    * @var \Spool\Zookeeper\Classes\Buffer
    */
    public $passwd;		    //buffer
    public function serialize(Oarchive &$out, string $tag, ConnectRequest &$v) : int{
	$rc = $out->startRecord($tag);
	$rc = $rc ?: $out->serializeInt('protocolVersion', $v->protocolVersion);
	$rc = $rc ?: $out->serializeLong('lastZxidSeen', $v->lastZxidSeen);
	$rc = $rc ?: $out->serializeInt('timeOut', $v->timeOut);
	$rc = $rc ?: $out->serializeLong('sessionId', $v->sessionId);
	$rc = $rc ?: $out->serializeBuffer('passwd', $v->passwd);
	$rc = $rc ?: $out->endRecord($tag);
	return $rc;
    }
    public function unserialize(Iarchive &$in, string $tag, ConnectRequest &$v) : int{
	$rc = $in->startRecord($tag);
	$rc = $rc ?: $in->deserializeInt('protocolVersion', $v->czxid);
	$rc = $rc ?: $in->deserializeLong('lastZxidSeen', $v->mzxid);
	$rc = $rc ?: $in->deserializeInt('timeOut', $v->ctime);
	$rc = $rc ?: $in->deserializeLong('sessionId', $v->mtime);
	$rc = $rc ?: $in->deserializeBuffer('passwd', $v->version);
	$rc = $rc ?: $in->endRecord($tag);
	return $rc;
    }
}
