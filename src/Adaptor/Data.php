<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Zkclient\Adaptor;

use Zkclient\Generated\Stat;

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
