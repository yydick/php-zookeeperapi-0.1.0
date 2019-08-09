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
use Zkclient\Generated\AclVector;
/**
 * Description of CreateTxn
 *
 * @author 陈浩波
 */
class CreateTxn {
    /**
    * @var string
    */
    public $path;	      //string
    /**
    * @var \Zkclient\Classes\Buffer
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
    /**
    * @var int
    */
    public $parentCVersion;	      //int32_t

    public function serialize(Oarchive &$out, string $tag, CreateTxn &$v): int {
	$rc = $out->startRecord($tag);
	$rc = $rc ?: $out->serializeString('path', $v->path);
	$rc = $rc ?: $out->serializeBuffer('data', $v->data);
	$rc = $rc ?: $this->acl->serialize($out, 'acl', $v->acl);
	$rc = $rc ?: $out->serializeInt('ephemeral', $v->ephemeral);
	$rc = $rc ?: $out->serializeInt('parentCVersion', $v->parentCVersion);
	$rc = $rc ?: $out->endRecord($tag);
	return $rc;
    }

    public function unserialize(Iarchive &$in, string $tag, CreateTxn &$v): int {
	$rc = $in->startRecord($tag);
	$rc = $rc ?: $in->deserializeString('path', $v->path);
	$rc = $rc ?: $in->deserializeBuffer('data', $v->data);
	$rc = $rc ?: $this->acl->unserialize($in, 'acl', $v->acl);
	$rc = $rc ?: $in->deserializeInt('ephemeral', $v->ephemeral);
	$rc = $rc ?: $in->deserializeInt('parentCVersion', $v->parentCVersion);
	$rc = $rc ?: $in->endRecord($tag);
	return $rc;
    }
}
