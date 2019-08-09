<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Zkclient\Adaptor;

use Zkclient\Generated\StringVector;

/**
 *
 * @author 陈浩波
 */
interface StringsCompletionT {
    public function __invoke(int $rc, StringVector &$strings, &$data);
}
