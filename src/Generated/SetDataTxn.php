<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Spool\Zookeeper\Generated;


use Spool\Zookeeper\Classes\Oarchive;
use Spool\Zookeeper\Classes\Iarchive;
use Spool\Zookeeper\Classes\Buffer;
/**
 * Description of SetDataTxn
 *
 * @author 陈浩波
 */
class SetDataTxn
{
    /**
    * @var int
    */
    public $path;	      //string
    /**
    * @var \Spool\Zookeeper\Classes\Buffer
    */
    public $data;	      //obj buffer
    /**
    * @var int
    */
    public $version;	      //int32_t

    public function serialize(Oarchive &$out, string $tag, SetDataTxn &$v): int {
	$rc = $out->startRecord($tag);
	$rc = $rc ?: $out->serializeString('path', $v->path);
	$rc = $rc ?: $out->serializeBuffer('data', $v->data);
	$rc = $rc ?: $out->serializeInt('version', $v->version);
	$rc = $rc ?: $out->endRecord($tag);
	return $rc;
    }

    public function unserialize(Iarchive &$in, string $tag, SetDataTxn &$v): int {
	$rc = $in->startRecord($tag);
	$rc = $rc ?: $in->deserializeString('path', $v->path);
	$rc = $rc ?: $in->deserializeBuffer('data', $v->data);
	$rc = $rc ?: $in->deserializeInt('version', $v->version);
	$rc = $rc ?: $in->endRecord($tag);
	return $rc;
    }
}
