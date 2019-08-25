<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Spool\Zookeeper\Lib;

//use Zkclient\Zoo\CompletionHeadT;

use Spool\Zookeeper\Adaptor\VoidCompletionT;
use Spool\Zookeeper\Adaptor\StringCompletionT;
use Spool\Zookeeper\Adaptor\DataCompletionT;
use Spool\Zookeeper\Adaptor\StatCompletionT;
use Spool\Zookeeper\Adaptor\StringsCompletionT;
use Spool\Zookeeper\Adaptor\StringsStatCompletionT;
use Spool\Zookeeper\Adaptor\AclCompletionT;

/**
 * Description of CompletionT
 *
 * @author 陈浩波
 */
class CompletionT {
    /**
    * @var int
    */
    public $type;		    //int 确认回调函数的类型
    /**
    * @var callback
    */
    public $fn;			    //回调函数指针
    /**
    * @var VoidCompletionT
    */
    public $void_result;    //void回调函数
    /**
    * @var StringCompletionT
    */
    public $string_result;  //string
    /**
    * @var DataCompletionT
    */
    public $data_result;    //data
    /**
    * @var StatCompletionT
    */
    public $stat_result;    //stat
    /**
    * @var StringsCompletionT
    */
    public $strings_result; //strings
    /**
    * @var StringsStatCompletionT
    */
    public $strings_stat_result;    //strings_stat
    /**
    * @var AclCompletionT
    */
    public $acl_result;     //acl
    /**
    * @var completionHeadT
    */
    public $clist;		    //多重调用的返回值	completionHeadT
    /**
     * 
     * @param int $type
     * @param callback $fn
     * @param \Spool\Zookeeper\Client\Zoo\completionHeadT $clist
     */
    public function __construct(int $type = 0, callback $fn = null, completionHeadT $clist = null) {
	$this->type = $type;
	$this->fn = $fn;
	$this->clist = $clist;
    }
}
