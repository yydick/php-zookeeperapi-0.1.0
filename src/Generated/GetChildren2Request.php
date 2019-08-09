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
 * Description of GetChildren2Request
 *
 * @author 陈浩波
 */
class GetChildren2Request {
    /**
    * @var string
    */
    public $path;	      //string
    /**
    * @var int
    */
    public $watch;	      //int32

    public function serialize(Oarchive &$out, string $tag, GetChildren2Request &$v): int {
	$rc = $out->startRecord($tag);
	$rc = $rc ?: $out->serializeString('path', $v->path);
	$rc = $rc ?: $out->serializeInt('watch', $v->watch);
	$rc = $rc ?: $out->endRecord($tag);
	return $rc;
    }

    public function unserialize(Iarchive &$in, string $tag, GetChildren2Request &$v): int {
	$rc = $in->startRecord($tag);
	$rc = $rc ?: $in->deserializeString('path', $v->path);
	$rc = $rc ?: $in->deserializeInt('watch', $v->watch);
	$rc = $rc ?: $in->endRecord($tag);
	return $rc;
    }
}
