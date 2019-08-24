<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Spool\Zookeeper\Generated;


use Spool\Zookeeper\Classes\Oarchive;
use Spool\Zookeeper\Classes\Iarchive;
use Spool\Zookeeper\Generated\TxnVector;
/**
 * Description of TxnVector
 *
 * @author 陈浩波
 */
class MultiTxn {
    /**
    * @var TxnVector
    */
    public $txns;	      //obj TxnVector
    public function serialize(Oarchive &$out, string $tag, MultiTxn &$v): int {
	$rc = $out->startRecord($tag);
	$rc = $rc ?: $this->data->serialize($out, 'txns', $v->txns);
	$rc = $rc ?: $out->endRecord($tag);
	return $rc;
    }

    public function unserialize(Iarchive &$in, string $tag, MultiTxn &$v): int {
	$rc = $in->startRecord($tag);
	$rc = $rc ?: $this->data->unserialize($in, 'txns', $v->txns);
	$rc = $rc ?: $in->endRecord($tag);
	return $rc;
    }
}
