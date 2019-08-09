<?php

namespace Spool\Zookeeper\System;

class Timeval
{
    public $tv_sec;
    public $tv_usec;
    
    public function __construct(){
        $tv = explode(' ', microtime());
//        var_dump($tv);exit;
        $this->tv_sec = $tv[1];
        $this->tv_usec = $tv[0];
    }
}
