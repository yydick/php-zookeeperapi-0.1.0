<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Spool\Zookeeper\Adaptor;

use Swoole\Lock;
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
    * @var \Swoole\Lock
    */
    public $lock;	    //obj SyncMutex	共享锁
    public function __construct(string $buffer = '', int $len = 0, int $curr_offset = 0) {
        $this->buffer = $buffer;
        $this->len = $len;
        $this->curr_offset = $curr_offset;
    }
    public function lock() {
	return $this->lock->lock();
    }
    public function unlock() {
	return $this->lock->unlock();
    }
}
