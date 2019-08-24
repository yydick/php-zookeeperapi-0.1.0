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
 * Description of GetChildrenRequest
 *
 * @author 陈浩波
 */
class GetChildrenRequest {
    /**
    * @var string
    */
    public $path;	      //string
    /**
    * @var int
    */
    public $watch;	      //int32

    public function serialize(Oarchive &$out, string $tag, GetChildrenRequest &$v): int {
	$rc = $out->startRecord($tag);
	$rc = $rc ?: $out->serializeString('path', $v->path);
	$rc = $rc ?: $out->serializeInt('watch', $v->watch);
	$rc = $rc ?: $out->endRecord($tag);
	return $rc;
    }

    public function unserialize(Iarchive &$in, string $tag, GetChildrenRequest &$v): int {
	$rc = $in->startRecord($tag);
	$rc = $rc ?: $in->deserializeString('path', $v->path);
	$rc = $rc ?: $in->deserializeInt('watch', $v->watch);
	$rc = $rc ?: $in->endRecord($tag);
	return $rc;
    }
}
