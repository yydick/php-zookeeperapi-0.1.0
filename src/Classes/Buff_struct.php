<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Spool\Zookeeper\Classes;

/**
 * Description of Buff_struct
 *
 * @author 陈浩波
 */
class Buff_struct
{
    /**
    * @var int
    */
    public $len; //int
    /**
    * @var int
    */
    public $off; //int
    /**
    * @var string
    */
    public $buffer; //string

    public function __construct(int $len = 0, int $off = 0, string $buffer = '') {
	$this->len = $len;
	$this->off = $off;
	$this->buffer = $buffer;
    }

    static public function resizeBuffer(Buff_struct &$s, int $newlen) {
	if ($s->len < 1) {
	    $s->len = $newlen;
	}
	while ($s->len < $newlen) {
	    $s->len *= 2;
	}
	return 0;
    }
    public function __toString() 
    {
	return $this->buffer;
    }
}
