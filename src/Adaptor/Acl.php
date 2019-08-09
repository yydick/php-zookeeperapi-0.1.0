<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Zkclient\Adaptor;

use Zkclient\Generated\AclVector;
use Zkclient\Generated\Stat;

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
