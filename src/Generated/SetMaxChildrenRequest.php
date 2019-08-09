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
 * Description of SetMaxChildrenRequest
 *
 * @author 陈浩波
 */
class SetMaxChildrenRequest
{
    /**
    * @var string
    */
    public $path;	      //string
    /**
    * @var int
    */
    public $max;	      //int32

    public function serialize(Oarchive &$out, string $tag, SetMaxChildrenRequest &$v): int {
	$rc = $out->startRecord($tag);
	$rc = $rc ?: $out->serializeString('path', $v->path);
	$rc = $rc ?: $out->serializeInt('max', $v->max);
	$rc = $rc ?: $out->endRecord($tag);
	return $rc;
    }

    public function unserialize(Iarchive &$in, string $tag, SetMaxChildrenRequest &$v): int {
	$rc = $in->startRecord($tag);
	$rc = $rc ?: $in->deserializeString('path', $v->path);
	$rc = $rc ?: $in->deserializeInt('max', $v->max);
	$rc = $rc ?: $in->endRecord($tag);
	return $rc;
    }
}
