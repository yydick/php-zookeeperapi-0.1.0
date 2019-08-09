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
 * Description of Acl
 *
 * @author 陈浩波
 */
class RequestHeader {
    /**
    * @var int
    */
    public $xid;	      //int32_t
    /**
    * @var int
    */
    public $type;	      //int32_t
    public function __construct(int $xid = 0, int $type = 0) {
        $this->xid = $xid;
        $this->type = $type;
    }
    public function serialize(Oarchive &$out, string $tag, RequestHeader &$v): int {
	$rc = $out->startRecord($tag);
	$rc = $rc ?: $out->serializeInt('xid', $v->xid);
	$rc = $rc ?: $out->serializeInt('type', $v->type);
	$rc = $rc ?: $out->endRecord($tag);
	return $rc;
    }

    public function unserialize(Iarchive &$in, string $tag, Acl &$v): int {
	$rc = $in->startRecord($tag);
	$rc = $rc ?: $in->deserializeInt('xid', $v->xid);
	$rc = $rc ?: $in->deserializeInt('type', $v->type);
	$rc = $rc ?: $in->endRecord($tag);
	return $rc;
    }
}
