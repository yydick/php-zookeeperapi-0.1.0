<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Zkclient\Generated;


use Zkclient\Classes\Oarchive;
use Zkclient\Classes\Iarchive;
use Zkclient\Generated\AclVector;

/**
 * Description of SetACLRequest
 *
 * @author 陈浩波
 */
class SetACLRequest {
    /**
    * @var string
    */
    public $path;	      //string
    /**
    * @var AclVector
    */
    public $acl;	      //obj AclVector
    /**
    * @var int
    */
    public $version;	      //int32

    public function serialize(Oarchive &$out, string $tag, SetACLRequest &$v): int {
	$rc = $out->startRecord($tag);
	$rc = $rc ?: $out->serializeString('path', $v->path);
	$rc = $rc ?: $this->acl->serialize($out, 'path', $v->acl);
	$rc = $rc ?: $out->serializeInt('max', $v->max);
	$rc = $rc ?: $out->endRecord($tag);
	return $rc;
    }

    public function unserialize(Iarchive &$in, string $tag, SetACLRequest &$v): int {
	$rc = $in->startRecord($tag);
	$rc = $rc ?: $in->deserializeString('path', $v->path);
	$rc = $rc ?: $this->acl->unserialize($in, 'path', $v->acl);
	$rc = $rc ?: $in->deserializeInt('max', $v->max);
	$rc = $rc ?: $in->endRecord($tag);
	return $rc;
    }
}
