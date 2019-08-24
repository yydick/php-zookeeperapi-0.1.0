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
use Spool\Zookeeper\Generated\AclVector;
/**
 * Description of QuorumAuthPacket
 *
 * @author 陈浩波
 */
class QuorumAuthPacket {

    /**
    * @var string
    */
    public $path;	      //string
    /**
    * @var \Spool\Zookeeper\Classes\Buffer
    */
    public $data;	      //obj buffer
    /**
    * @var AclVector
    */
    public $acl;	      //AclVector
    /**
    * @var int
    */
    public $ephemeral;	      //int32_t

    public function serialize(Oarchive &$out, string $tag, QuorumAuthPacket &$v): int {
	$rc = $out->startRecord($tag);
	$rc = $rc ?: $out->serializeString('path', $v->path);
	$rc = $rc ?: $out->serializeBuffer('data', $v->data);
	$rc = $rc ?: $this->acl->serialize($out, 'acl', $v->acl);
	$rc = $rc ?: $out->serializeInt('ephemeral', $v->ephemeral);
	$rc = $rc ?: $out->endRecord($tag);
	return $rc;
    }

    public function unserialize(Iarchive &$in, string $tag, QuorumAuthPacket &$v): int {
	$rc = $in->startRecord($tag);
	$rc = $rc ?: $in->deserializeString('path', $v->path);
	$rc = $rc ?: $in->deserializeBuffer('data', $v->data);
	$rc = $rc ?: $this->acl->unserialize($in, 'acl', $v->acl);
	$rc = $rc ?: $in->deserializeInt('ephemeral', $v->ephemeral);
	$rc = $rc ?: $in->endRecord($tag);
	return $rc;
    }
}
