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
 * Description of QuorumAuthPacket
 *
 * @author 陈浩波
 */
class QuorumAuthPacket {
    /**
    * @var int
    */
    public $magic;	      //int64_t
    /**
    * @var int
    */
    public $status;	      //int32_t
    /**
    * @var {\Spool\Zookeeper\Classes\Buffer|Buffer}
    */
    public $token;	      //obj buffer
    public function serialize(Oarchive &$out, string $tag, QuorumAuthPacket &$v): int {
	$rc = $out->startRecord($tag);
	$rc = $rc ?: $out->serializeLong('zxid', $v->zxid);
	$rc = $rc ?: $out->serializeInt('type', $v->type);
	$rc = $rc ?: $out->serializeBuffer('data', $v->data);
	$rc = $rc ?: $out->endRecord($tag);
	return $rc;
    }

    public function unserialize(Iarchive &$in, string $tag, QuorumAuthPacket &$v): int {
	$rc = $in->startRecord($tag);
	$rc = $rc ?: $in->deserializeLong('zxid', $v->zxid);
	$rc = $rc ?: $in->deserializeInt('type', $v->type);
	$rc = $rc ?: $in->deserializeBuffer('data', $v->data);
	$rc = $rc ?: $in->endRecord($tag);
	return $rc;
    }
}
