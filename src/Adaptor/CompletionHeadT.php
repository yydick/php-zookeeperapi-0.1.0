<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Spool\Zookeeper\Adaptor;

use Spool\Zookeeper\Adaptor\CompletionListT;
use Swoole\Lock;

/**
 * Description of CompletionHeadT
 *
 * @author 大天使长
 */
class CompletionHeadT {
    /**
     * @var CompletionListT
     */
    public $head;
    /**
     * @var CompletionListT
     */
    public $last;
    /**
     * @var Lock
     */
    public $cond;
    /**
     * @var Lock
     */
    public $lock;
}
