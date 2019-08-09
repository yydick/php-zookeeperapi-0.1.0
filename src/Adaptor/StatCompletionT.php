<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Zkclient\Adaptor;

use Zkclient\Generated\Stat;

/**
 *
 * @author 陈浩波
 */
interface StatCompletionT {
    public function __invoke(int $rc, Stat &$stat, &$data);
}
