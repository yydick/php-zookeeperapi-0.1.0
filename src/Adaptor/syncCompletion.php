<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Spool\Zookeeper\Adaptor;

use Spool\Zookeeper\Adaptor\Str;
use Spool\Zookeeper\Generated\Stat;
use Spool\Zookeeper\Adaptor\Data;
use Spool\Zookeeper\Adaptor\Acl;
use Spool\Zookeeper\Generated\StringVector;
use Spool\Zookeeper\Adaptor\StrsStat;

/**
 * Description of syncCompletion
 *
 * @author 陈浩波
 */
class syncCompletion {
    /**
    * @var int
    */
    public $rc;			    //int
    /**
    * @var Str|Stat|Data|Acl|StringVector|StrsStat
    */
    public $u;			    //str struct | Stat | data | acl | String_vector | strs_stat
    /**
    * @var int
    */
    public $complete;		    //int
    /**
    * @var \SyncSemaphore
    */
    public $cond;
    /**
    * @var \SyncMutex
    */
    public $lock;
}
