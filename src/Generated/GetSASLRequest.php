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

/**
 * Description of GetSASLRequest
 *
 * @author 陈浩波
 */
class GetSASLRequest {
    /**
    * @var \Zkclient\Classes\Buffer
    */
    public $token;	      //buffer

    public function serialize(Oarchive &$out, string $tag, GetSASLRequest &$v): int {
	$rc = $out->startRecord($tag);
	$rc = $rc ?: $out->serializeBuffer('token', $v->token);
	$rc = $rc ?: $out->endRecord($tag);
	return $rc;
    }

    public function unserialize(Iarchive &$in, string $tag, GetSASLRequest &$v): int {
	$rc = $in->startRecord($tag);
	$rc = $rc ?: $in->deserializeBuffer('token', $v->token);
	$rc = $rc ?: $in->endRecord($tag);
	return $rc;
    }
}
