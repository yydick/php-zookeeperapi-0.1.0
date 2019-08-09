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

/**
 * Description of GetDataRequest
 *
 * @author 陈浩波
 */
class SetDataRequest
{
    /**
    * @var int
    */
    public $path;	      //string
    /**
    * @var \Zkclient\Classes\Buffer
    */
    public $data;	      //buffer
    /**
    * @var int
    */
    public $version;	      //int32_t

    public function serialize(Oarchive &$out, string $tag, SetDataRequest &$v): int {
	$rc = $out->startRecord($tag);
	$rc = $rc ?: $out->serializeString('path', $v->path);
	$rc = $rc ?: $out->serializeBuffer('data', $v->data);
	$rc = $rc ?: $out->serializeInt('version', $v->version);
	$rc = $rc ?: $out->endRecord($tag);
	return $rc;
    }

    public function unserialize(Iarchive &$in, string $tag, SetDataRequest &$v): int {
	$rc = $in->startRecord($tag);
	$rc = $rc ?: $in->deserializeString('path', $v->path);
	$rc = $rc ?: $in->deserializeBuffer('data', $v->data);
	$rc = $rc ?: $in->deserializeInt('version', $v->version);
	$rc = $rc ?: $in->endRecord($tag);
	return $rc;
    }
}
