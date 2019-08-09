<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Zkclient\Generated;


use Zkclient\Classes\Oarchive;
use Zkclient\Classes\Iarchive;
use Zkclient\Classes\Buffer;
use Zkclient\Generated\IdVector;
/**
 * Description of QuorumPacket
 *
 * @author 陈浩波
 */
class QuorumPacket {
    /**
    * @var int
    */
    public $type;	      //int32_t
    /**
    * @var int
    */
    public $zxid;	      //int64_t
    /**
    * @var \Zkclient\Classes\Buffer
    */
    public $data;	      //obj buffer
    /**
    * @var IdVector
    */
    public $authinfo;	      //obj IdVector

    public function serialize(Oarchive &$out, string $tag, QuorumPacket &$v): int {
	$rc = $out->startRecord($tag);
	$rc = $rc ?: $out->serializeInt('type', $v->type);
	$rc = $rc ?: $out->serializeLong('zxid', $v->zxid);
	$rc = $rc ?: $out->serializeBuffer('data', $v->data);
	$rc = $rc ?: $this->authinfo->serialize($out, 'authinfo', $v->authinfo);
	$rc = $rc ?: $out->endRecord($tag);
	return $rc;
    }

    public function unserialize(Iarchive &$in, string $tag, QuorumPacket &$v): int {
	$rc = $in->startRecord($tag);
	$rc = $rc ?: $in->deserializeInt('type', $v->type);
	$rc = $rc ?: $in->deserializeLong('zxid', $v->zxid);
	$rc = $rc ?: $in->deserializeBuffer('data', $v->data);
	$rc = $rc ?: $this->authinfo->unserialize($in, 'authinfo', $v->authinfo);
	$rc = $rc ?: $in->endRecord($tag);
	return $rc;
    }
}
