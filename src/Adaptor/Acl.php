<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Spool\Zookeeper\Adaptor;

use Spool\Zookeeper\Generated\AclVector;
use Spool\Zookeeper\Generated\Stat;

/**
 * Description of Acl
 *
 * @author 陈浩波
 */
class Acl {
    /**
    * @var AclVector
    */
    public $acl;		//AclVector
    /**
    * @var Stat
    */
    public $stat;		//Stat
}
