<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Spool\Zookeeper\Adaptor;

use Spool\Zookeeper\Generated\StringVector;

/**
 *
 * @author 陈浩波
 */
interface StringsCompletionT {
    public function __invoke(int $rc, StringVector &$strings, &$data);
}
