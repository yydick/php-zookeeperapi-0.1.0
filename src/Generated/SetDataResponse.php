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
 * Description of GetDataRequest
 *
 * @author 陈浩波
 */
class SetDataResponse
{
    /**
    * @var Stat
    */
    public $stat;	      //Stat
    public function serialize(Oarchive &$out, string $tag, SetDataResponse &$v): int {
	$rc = $out->startRecord($tag);
	$rc = $rc ?: $this->stat->serialize($out, 'stat', $v->stat);
	$rc = $rc ?: $out->endRecord($tag);
	return $rc;
    }

    public function unserialize(Iarchive &$in, string $tag, SetDataResponse &$v): int {
	$rc = $in->startRecord($tag);
	$rc = $rc ?: $this->stat->deserialize($in, 'stat', $v->stat);
	$rc = $rc ?: $in->endRecord($tag);
	return $rc;
    }
}
