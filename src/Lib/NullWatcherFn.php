<?php

namespace Spool\Zookeeper\Lib;

use Spool\Zookeeper\Lib\WatcherFn;
use Spool\Zookeeper\Lib\ZhandleT;

class NullWatcherFn implements WatcherFn
{
    public function __invoke(ZhandleT &$zh, int $type, int $state, string $path, $watcherCtx){}
}
