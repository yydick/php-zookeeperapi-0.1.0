<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Spool\Zookeeper\Adaptor;

use Spool\Zookeeper\Adaptor\BufferListT;
use Swoole\Lock;

/**
 * Description of bufferHeadT
 *
 * @author 陈浩波
 */


class BufferHeadT {
    /**
     * @var BufferListT 列表头
     */
    public $head;
    /**
     * @var BufferListT 列表尾
     */
    public $last;
    /**
     * \SyncSemaphore 列表尾
     * @var Lock 
     */
    public $cond;
    /**
     * \SyncMutex 列表尾
     * @var Lock 
     */
    public $lock;
    public function __construct() {
        $this->head = new BufferListT();
        $this->last = new BufferListT();
        $this->lock = new Lock(SWOOLE_MUTEX);
        $this->cond = new Lock(SWOOLE_SEM);
    }
}
