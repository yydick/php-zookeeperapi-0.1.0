<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Spool\Zookeeper\Adaptor;

use Spool\Zookeeper\Generated\Stat;

/**
 * Description of Data
 *
 * @author 陈浩波
 */
class Data {
    /**
    * @var string
    */
    public $buffer;	    //char*
    /**
    * @var int
    */
    public $buffLen;	    //int
    /**
    * @var Stat
    */
    public $stat;	    //Stat
}
