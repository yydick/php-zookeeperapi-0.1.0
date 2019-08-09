<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Zkclient\Generated;


use Zkclient\Classes\Oarchive;
use Zkclient\Classes\Iarchive;
use Zkclient\Generated\Stat;

/**
 * Description of SetACLResponse
 *
 * @author 陈浩波
 */
class SetACLResponse {
    /**
    * @var Stat
    */
    public $stat;	      //obj Stat
    public function serialize(Oarchive &$out, string $tag, SetACLResponse &$v): int {
	$rc = $out->startRecord($tag);
	$rc = $rc ?: $this->stat->serialize($out, 'stat', $v->stat);
	$rc = $rc ?: $out->endRecord($tag);
	return $rc;
    }

    public function unserialize(Iarchive &$in, string $tag, SetACLResponse &$v): int {
	$rc = $in->startRecord($tag);
	$rc = $rc ?: $this->stat->unserialize($in, 'stat', $v->stat);
	$rc = $rc ?: $in->endRecord($tag);
	return $rc;
    }
}
