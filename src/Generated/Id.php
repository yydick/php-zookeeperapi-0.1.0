<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Spool\Zookeeper\Generated;

use Spool\Zookeeper\Classes\Oarchive;
use Spool\Zookeeper\Classes\Iarchive;
/**
 * Description of Id
 *
 * @author 陈浩波
 */
class Id{
    /**
    * @var string
    */
    public $scheme;             //string
    /**
    * @var string
    */
    public $id;                 //string

    public function serialize(Oarchive &$out, string $tag, Id &$v): int {
	$rc = $out->startRecord($tag);
	$rc = $rc ?: $out->serializeString('scheme', $v->scheme);
	$rc = $rc ?: $out->serializeString('id', $v->id);
	$rc = $rc ?: $out->endRecord($tag);
	return $rc;
    }

    public function unserialize(Iarchive &$in, string $tag, Id &$v): int {
	$rc = $in->startRecord($tag);
	$rc = $rc ?: $in->deserializeString('scheme', $v->scheme);
	$rc = $rc ?: $in->deserializeString('id', $v->id);
	$rc = $rc ?: $in->endRecord($tag);
	return $rc;
    }
}
