<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Spool\Zookeeper\Adaptor;

use Spool\Zookeeper\Adaptor\BufferListT;

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
     * @var \SyncSemaphore 列表尾
     */
    public $cond;
    /**
     * @var \SyncMutex 列表尾
     */
    public $lock;
}
