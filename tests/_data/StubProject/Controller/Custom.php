<?php

namespace StubProject\Controller;

use Phalcon\Http\Response;
use Phalcon\Mvc\Controller;

class Custom extends Controller
{
    public function indexAction()
    {
        return new Response(__CLASS__ . '::' . __FUNCTION__ . '()');
    }
    
    public function fooAction()
    {
        echo __CLASS__ . '::' . __FUNCTION__ . '()';
    }

    public function postAction()
    {
        echo __CLASS__ . '::' . __FUNCTION__ . '()';
    }
}
