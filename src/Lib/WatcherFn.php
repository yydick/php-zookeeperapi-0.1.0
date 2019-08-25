<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Spool\Zookeeper\Lib;

use Spool\Zookeeper\Lib\ZhandleT;
/**
 * Description of WatcherFn
 *
 * @author 大天使长
 */
interface WatcherFn {
    public function __invoke(ZhandleT &$zh, int $type, int $state, string $path, $watcherCtx);
}
