<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Zkclient\Generated;


use Zkclient\Classes\Oarchive;
use Zkclient\Classes\Iarchive;
use Zkclient\Generated\StringVector;

/**
 * Description of GetDataResponse
 *
 * @author 陈浩波
 */
class GetDataResponse {
    /**
    * @var StringVector
    */
    public $children;	      //obj StringVector
    public function serialize(Oarchive &$out, string $tag, GetDataResponse &$v): int {
	$rc = $out->startRecord($tag);
	$rc = $rc ?: $this->children->serialize($out, 'children', $v->children);
	$rc = $rc ?: $out->endRecord($tag);
	return $rc;
    }

    public function unserialize(Iarchive &$in, string $tag, GetDataResponse &$v): int {
	$rc = $in->startRecord($tag);
	$rc = $rc ?: $this->children->unserialize($in, 'children', $v->children);
	$rc = $rc ?: $in->endRecord($tag);
	return $rc;
    }
}
