<?php

namespace Zkclient\Classes;

use Zkclient\Adaptor\VoidCompletionT;
use Zkclient\Adaptor\AuthInfo;
use Zkclient\Adaptor\AuthListHead;

class AuthCompletionListT extends \SplQueue
{
    /**
    * @var VoidCompletionT
    */
    public $completion;	    //VoidCompletionT
    /**
    * @var string
    */
    public $auth_data;	    //string
//    public $next;
    
    public function get_auth_completions(AuthListHead &$auth_list) : AuthInfo
    {
        if(!$auth_list->auth){
            $auth_list->auth->auth = &$this;
            return;
        } else {
	    $element = new AuthInfo();
	    $element = &$auth_list->auth;
	    $element->rewind();
	}
        while($element->valid()){
	    $tmp = &$element->current();
            if($tmp->completion){
                $this->add_auth_completion($tmp->completion, $tmp->data);
            }
	    $tmp->completion = NULL;
	    $element->next();
        }
    }
    protected function add_auth_completion(VoidCompletionT $completion, $data)
    {
        $element = new AuthCompletionListT();
	$element->completion = &$completion;
	$element->auth_data = $data;
        $this->push($element);
    }
}
