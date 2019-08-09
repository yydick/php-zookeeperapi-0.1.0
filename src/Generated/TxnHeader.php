<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Zkclient\Generated;


use Zkclient\Classes\Oarchive;
use Zkclient\Classes\Iarchive;

/**
 * Description of TxnHeader
 *
 * @author 陈浩波
 */
class TxnHeader
{
    /**
    * @var int
    */
    public $clientId;	      //int64_t
    /**
    * @var int
    */
    public $cxid;	      //int32_t
    /**
    * @var int
    */
    public $zxid;	      //int64_t
    /**
    * @var int
    */
    public $time;	      //int64_t
    /**
    * @var int
    */
    public $type;	      //int32_t

    public function serialize(Oarchive &$out, string $tag, TxnHeader &$v) : int {
	$rc = $out->startRecord($tag);
	$rc = $rc ?: $out->serializeLong('clientId', $v->clientId);
	$rc = $rc ?: $out->serializeInt('cxid', $v->cxid);
	$rc = $rc ?: $out->serializeLong('zxid', $v->zxid);
	$rc = $rc ?: $out->serializeLong('time', $v->time);
	$rc = $rc ?: $out->serializeInt('type', $v->type);
	$rc = $rc ?: $out->endRecord($tag);
	return $rc;
    }

    public function unserialize(Iarchive &$in, string $tag, TxnHeader &$v) : int {
	$rc = $in->startRecord($tag);
	$rc = $rc ?: $in->deserializeLong('clientId', $v->clientId);
	$rc = $rc ?: $in->deserializeInt('cxid', $v->cxid);
	$rc = $rc ?: $in->deserializeLong('zxid', $v->zxid);
	$rc = $rc ?: $in->deserializeLong('time', $v->time);
	$rc = $rc ?: $in->deserializeInt('type', $v->type);
	$rc = $rc ?: $in->endRecord($tag);
	return $rc;
    }
}
