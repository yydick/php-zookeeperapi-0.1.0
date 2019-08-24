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
 * Description of CreateSessionTxn
 *
 * @author 陈浩波
 */
class CreateSessionTxn {

    /**
    * @var int
    */
    public $timeOut;	      //int32_t

    public function serialize(Oarchive &$out, string $tag, CreateSessionTxn &$v): int {
	$rc = $out->startRecord($tag);
	$rc = $rc ?: $out->serializeInt('timeOut', $v->timeOut);
	$rc = $rc ?: $out->endRecord($tag);
	return $rc;
    }

    public function unserialize(Iarchive &$in, string $tag, CreateSessionTxn &$v): int {
	$rc = $in->startRecord($tag);
	$rc = $rc ?: $in->deserializeInt('timeOut', $v->timeOut);
	$rc = $rc ?: $in->endRecord($tag);
	return $rc;
    }
}
