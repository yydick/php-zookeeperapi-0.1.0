<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Zkclient\Generated;


use Zkclient\Classes\Oarchive;
use Zkclient\Classes\Iarchive;
use Zkclient\Generated\Id;

/**
 * Description of Acl
 *
 * @author 陈浩波
 */
class Acl {

    /**
    * @var int
    */
    public $perms;	      //int32
    /**
     * @var Id 
     */
    public $id;		      //obj Id

    public function serialize(Oarchive &$out, string $tag, Acl &$v): int {
	$rc = $out->startRecord($tag);
	$rc = $rc ?: $out->serializeInt('perms', $v->perms);
	$rc = $rc ?: $this->id->serialize($out, 'id', $v->id);
	$rc = $rc ?: $out->endRecord($tag);
	return $rc;
    }

    public function unserialize(Iarchive &$in, string $tag, Acl &$v): int {
	$rc = $in->startRecord($tag);
	$rc = $rc ?: $in->deserializeInt('perms', $v->perms);
	$rc = $rc ?: $this->id->unserialize($in, 'id', $v->id);
	$rc = $rc ?: $in->endRecord($tag);
	return $rc;
    }
}
