<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Zkclient\Adaptor;

/**
 * Description of BufferList
 *
 * @author 陈浩波
 */
class BufferListT extends \SplQueue{
    /**
    * @var string
    */
    public $buffer;	    //string	字符串的值
    /**
    * @var int
    */
    public $len;	    //int	字符串长度
    /**
    * @var int
    */
    public $curr_offset;    //int	头部的偏移量，后面是内容
    /**
    * @var BufferListT
    */
    public $next;	    //obj BufferList	下一个类的指针
    /**
    * @var \SyncMutex
    */
    public $lock;	    //obj SyncMutex	共享锁
    public function lock() {
	return $this->lock->lock();
    }
    public function unlock() {
	return $this->lock->unlock();
    }
}
