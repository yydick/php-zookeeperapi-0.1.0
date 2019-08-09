<?php

namespace Spool\Zookeeper\System;

class Utsname{
    public $sysname;
    public $nodename;
    public $release;
    public $version;
    public $machine;
    public function uname(){
        $this->sysname = php_uname('s');
        $this->nodename = php_uname('n');
        $this->release = php_uname('r');
        $this->version = php_uname('v');
        $this->machine = php_uname('m');
    }
}
