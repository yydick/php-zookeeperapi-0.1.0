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
use Zkclient\Generated\Stat;

/**
 * Description of GetACLResponse
 *
 * @author 陈浩波
 */
class GetACLResponse {
    /**
    * @var AclVector
    */
    public $acl;	      //obj AclVector
    /**
    * @var Stat
    */
    public $stat;	      //obj Stat

    public function serialize(Oarchive &$out, string $tag, GetACLResponse &$v): int {
	$rc = $out->startRecord($tag);
	$rc = $rc ?: $this->acl->serialize($out, 'acl', $v->acl);
	$rc = $rc ?: $this->stat->serialize($out, 'stat', $v->stat);
	$rc = $rc ?: $out->endRecord($tag);
	return $rc;
    }

    public function unserialize(Iarchive &$in, string $tag, GetACLResponse &$v): int {
	$rc = $in->startRecord($tag);
	$rc = $rc ?: $this->acl->unserialize($in, 'acl', $v->acl);
	$rc = $rc ?: $this->stat->unserialize($in, 'stat', $v->stat);
	$rc = $rc ?: $in->endRecord($tag);
	return $rc;
    }
}
