<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Spool\Zookeeper\Adaptor;

/**
 *
 * @author 陈浩波
 */
interface VoidCompletionT {
    public function __invoke(int $rc, &$data);
}
