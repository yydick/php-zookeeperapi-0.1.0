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
 * Description of CreateRequest
 *
 * @author 陈浩波
 */
class CreateRequest {

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
    public $acl;	      //obj AclVector
    /**
    * @var int
    */
    public $flags;	      //int32

    public function serialize(Oarchive &$out, string $tag, CreateRequest &$v): int {
	$rc = $out->startRecord($tag);
	$rc = $rc ?: $out->serializeString('path', $v->path);
	$rc = $rc ?: $out->serializeBuffer($out, 'data', $v->data);
	$rc = $rc ?: $this->acl->serialize($out, 'acl', $v->acl);
	$rc = $rc ?: $out->serializeInt('flags', $v->flags);
	$rc = $rc ?: $out->endRecord($tag);
	return $rc;
    }

    public function unserialize(Iarchive &$in, string $tag, CreateRequest &$v): int {
	$rc = $in->startRecord($tag);
	$rc = $rc ?: $in->deserializeString('path', $v->path);
	$rc = $rc ?: $in->deserializeBuffer($in, 'data', $v->data);
	$rc = $rc ?: $this->acl->unserialize($in, 'acl', $v->acl);
	$rc = $rc ?: $in->deserializeInt('flags', $v->flags);
	$rc = $rc ?: $in->endRecord($tag);
	return $rc;
    }
}
