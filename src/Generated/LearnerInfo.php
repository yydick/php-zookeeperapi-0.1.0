<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Spool\Zookeeper\Generated;


use Spool\Zookeeper\Classes\Oarchive;
use Spool\Zookeeper\Classes\Iarchive;

/**
 * Description of LearnerInfo
 *
 * @author 陈浩波
 */
class LearnerInfo {
    /**
    * @var int
    */
    public $serverid;	      //int64_t
    /**
    * @var int
    */
    public $protocolVersion; //int32_t

    public function serialize(Oarchive &$out, string $tag, LearnerInfo &$v): int {
	$rc = $out->startRecord($tag);
	$rc = $rc ?: $out->serializeLong('serverid', $v->serverid);
	$rc = $rc ?: $out->serializeInt('protocolVersion', $v->protocolVersion);
	$rc = $rc ?: $out->endRecord($tag);
	return $rc;
    }

    public function unserialize(Iarchive &$in, string $tag, LearnerInfo &$v): int {
	$rc = $in->startRecord($tag);
	$rc = $rc ?: $in->deserializeLong('serverid', $v->serverid);
	$rc = $rc ?: $in->deserializeInt('protocolVersion', $v->protocolVersion);
	$rc = $rc ?: $in->endRecord($tag);
	return $rc;
    }
}
