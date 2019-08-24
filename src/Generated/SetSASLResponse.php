<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Spool\Zookeeper\Generated;


use Spool\Zookeeper\Classes\Oarchive;
use Spool\Zookeeper\Classes\Iarchive;
use Spool\Zookeeper\Classes\Buffer;

/**
 * Description of SetSASLResponse
 *
 * @author 陈浩波
 */
class SetSASLResponse {

    /**
    * @var \Spool\Zookeeper\Classes\Buffer
    */
    public $token;	      //buffer

    public function serialize(Oarchive &$out, string $tag, SetSASLResponse &$v) : int {
	$rc = $out->startRecord($tag);
	$rc = $rc ?: $out->serializeBuffer('token', $v->token);
	$rc = $rc ?: $out->endRecord($tag);
	return $rc;
    }

    public function unserialize(Iarchive &$in, string $tag, SetSASLResponse &$v) : int {
	$rc = $in->startRecord($tag);
	$rc = $rc ?: $in->deserializeBuffer('token', $v->token);
	$rc = $rc ?: $in->endRecord($tag);
	return $rc;
    }
}
