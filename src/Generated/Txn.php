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
 * Description of Txn
 *
 * @author 陈浩波
 */
class Txn
{
    /**
    * @var int
    */
    public $type;	      //int32_t
    /**
    * @var Buffer
    */
    public $data;	      //obj Buffer

    public function serialize(Oarchive &$out, string $tag, Txn &$v): int {
	$rc = $out->startRecord($tag);
	$rc = $rc ?: $out->serializeInt('type', $v->type);
	$rc = $rc ?: $out->serializeBuffer('data', $v->data);
	$rc = $rc ?: $out->endRecord($tag);
	return $rc;
    }

    public function unserialize(Iarchive &$in, string $tag, Txn &$v): int {
	$rc = $in->startRecord($tag);
	$rc = $rc ?: $in->deserializeInt('type', $v->type);
	$rc = $rc ?: $in->deserializeBuffer('data', $v->data);
	$rc = $rc ?: $in->endRecord($tag);
	return $rc;
    }
}
