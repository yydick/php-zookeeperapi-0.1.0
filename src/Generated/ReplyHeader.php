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
 * Description of ReplyHeader
 *
 * @author 陈浩波
 */
class ReplyHeader {
    /**
    * @var int
    */
    public $xid;	      //int32_t
    /**
    * @var int
    */
    public $zxid;	      //int64_t
    /**
    * @var int
    */
    public $err;	      //int32_t
    public function serialize(Oarchive &$out, string $tag, ReplyHeader &$v): int {
	$rc = $out->startRecord($tag);
	$rc = $rc ?: $out->serializeInt('xid', $v->xid);
	$rc = $rc ?: $out->serializeLong('zxid', $v->zxid);
	$rc = $rc ?: $out->serializeInt('err', $v->err);
	$rc = $rc ?: $out->endRecord($tag);
	return $rc;
    }

    public function unserialize(Iarchive &$in, string $tag, ReplyHeader &$v): int {
	$rc = $in->startRecord($tag);
	$rc = $rc ?: $in->deserializeInt('xid', $v->xid);
	$rc = $rc ?: $in->deserializeLong('zxid', $v->zxid);
	$rc = $rc ?: $in->deserializeInt('err', $v->err);
	$rc = $rc ?: $in->endRecord($tag);
	return $rc;
    }
}
