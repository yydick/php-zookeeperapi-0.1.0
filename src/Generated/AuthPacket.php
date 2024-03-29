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
 * Description of AuthPacket
 *
 * @author 陈浩波
 */
class AuthPacket {

    /**
    * @var int
    */
    public $type;	    //int32_t
    /**
    * @var int
    */
    public $scheme;	    //int32_t
    /**
     * @var Buffer 
     */
    public $auth;	    //buffer

    public function __construct() {
	$this->type = 0;
	$this->scheme = '';
	$this->auth = new Buffer();
    }
    public function serialize(Oarchive &$out, string $tag, AuthPacket &$v): int {
	$rc = $out->startRecord($tag);
	$rc = $rc ?: $out->serializeInt('type', $v->type);
	$rc = $rc ?: $out->serializeString('scheme', $v->scheme);
	$rc = $rc ?: $out->serializeBuffer('auth', $v->auth);
	$rc = $rc ?: $out->endRecord($tag);
	return $rc;
    }

    public function unserialize(Iarchive &$in, string $tag, AuthPacket &$v): int {
	$rc = $in->startRecord($tag);
	$rc = $rc ?: $in->deserializeInt('type', $v->type);
	$rc = $rc ?: $in->deserializeString('scheme', $v->scheme);
	$rc = $rc ?: $in->deserializeBuffer('auth', $v->auth);
	$rc = $rc ?: $in->endRecord($tag);
	return $rc;
    }
}
