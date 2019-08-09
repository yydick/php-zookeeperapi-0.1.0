<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Zkclient\Adaptor;

/**
 * Description of AdaptorThreads
 *
 * @author 陈浩波
 */
class AdaptorThreads {
    /**
     * @var int 
     */
    public $io;
    /**
     * @var int 
     */
    public $completion;
    /**
     * @var int 
     */
    public $threadsToWait;
    /**
     * @var \SyncSemaphore 
     */
    public $cond;
    /**
     * @var \SyncMutex 
     */
    public $lock;
    /**
     * @var \SyncMutex 
     */
    public $zh_lock;
    /**
     * @var array[1] socket 
     */
    public $self_pipe;
}
