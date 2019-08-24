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
 * Description of GetMaxChildrenRequest
 *
 * @author 陈浩波
 */
class GetMaxChildrenRequest {
    /**
    * @var int
    */
    public $max;	      //int32_t

    public function serialize(Oarchive &$out, string $tag, GetMaxChildrenRequest &$v): int {
	$rc = $out->startRecord($tag);
	$rc = $rc ?: $out->serializeInt('max', $v->max);
	$rc = $rc ?: $out->endRecord($tag);
	return $rc;
    }

    public function unserialize(Iarchive &$in, string $tag, GetMaxChildrenRequest &$v): int {
	$rc = $in->startRecord($tag);
	$rc = $rc ?: $in->deserializeInt('max', $v->max);
	$rc = $rc ?: $in->endRecord($tag);
	return $rc;
    }
}
