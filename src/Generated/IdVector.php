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
 * Description of IdVector
 *
 * @author 陈浩波
 */
class IdVector {
    /**
    * @var int
    */
    public $count;	      //int32_t
    /**
    * @var Id
    */
    public $data;	      //obj Id

    public function serialize(Oarchive &$out, string $tag, IdVector &$v): int {
	$rc = $out->startRecord($tag);
	$rc = $rc ?: $out->serializeInt('count', $v->count);
	$rc = $rc ?: $this->data->serialize($out, 'data', $v->data);
	$rc = $rc ?: $out->endRecord($tag);
	return $rc;
    }

    public function unserialize(Iarchive &$in, string $tag, IdVector &$v): int {
	$rc = $in->startRecord($tag);
	$rc = $rc ?: $in->deserializeInt('count', $v->count);
	$rc = $rc ?: $this->data->unserialize($in, 'data', $v->data);
	$rc = $rc ?: $in->endRecord($tag);
	return $rc;
    }
}
