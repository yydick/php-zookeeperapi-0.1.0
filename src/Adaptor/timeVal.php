<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Spool\Zookeeper\Adaptor;

/**
 * Description of timeVal
 *
 * @author 陈浩波
 */
class timeVal {
    /**
    * @var int
    */
    public $tv_sec; /* int Seconds. */
    /**
    * @var int
    */
    public $tv_usec; /* int Microseconds. */

    public function __construct() {
	$this->getNow();
    }

    public function getNow() {
	$timezone = date_default_timezone_get();
	$time = \microtime();
	$tv = \explode(' ', $time);
	$this->tv_sec = $tv[1];
	$this->tv_usec = $tv[0] * 1000000;
	date_default_timezone_set($timezone);
    }

}
