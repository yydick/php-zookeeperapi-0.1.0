<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Spool\Zookeeper\Generated;


use Spool\Zookeeper\Classes\Oarchive;
use Spool\Zookeeper\Classes\Iarchive;
use Spool\Zookeeper\Generated\Acl;

/**
 * Description of AclVector
 *
 * @author 陈浩波
 */
class AclVector {

    /**
    * @var int
    */
    public $count;	      //int32
    /**
     * @var Acl 
     */
    public $data;	      //obj Acl

    public function serialize(Oarchive &$out, string $tag, AclVector &$v): int {
	$rc = $out->startRecord($tag);
	$rc = $rc ?: $out->serializeInt('count', $v->count);
	$rc = $rc ?: $this->data->serialize($out, 'data', $v->data);
	$rc = $rc ?: $out->endRecord($tag);
	return $rc;
    }

    public function unserialize(Iarchive &$in, string $tag, AclVector &$v): int {
	$rc = $in->startRecord($tag);
	$rc = $rc ?: $in->deserializeInt('count', $v->count);
	$rc = $rc ?: $this->data->unserialize($in, 'data', $v->data);
	$rc = $rc ?: $in->endRecord($tag);
	return $rc;
    }
}
