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
 * Description of GetDataRequest
 *
 * @author 陈浩波
 */
class GetDataRequest {
    /**
    * @var string
    */
    public $path;	      //string
    /**
    * @var int
    */
    public $watch;	      //int32_t
    public function serialize(Oarchive &$out, string $tag, GetDataRequest &$v): int {
	$rc = $out->startRecord($tag);
	$rc = $rc ?: $out->serializeString('path', $v->path);
	$rc = $rc ?: $out->serializeInt('watch', $v->watch);
	$rc = $rc ?: $out->endRecord($tag);
	return $rc;
    }

    public function unserialize(Iarchive &$in, string $tag, GetDataRequest &$v): int {
	$rc = $in->startRecord($tag);
	$rc = $rc ?: $in->deserializeString('path', $v->path);
	$rc = $rc ?: $in->deserializeInt('watch', $v->watch);
	$rc = $rc ?: $in->endRecord($tag);
	return $rc;
    }
}
