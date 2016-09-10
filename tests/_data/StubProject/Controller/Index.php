<?php

namespace StubProject\Controller;

use Phalcon\Http\Response;
use Phalcon\Mvc\Controller;

class Index extends Controller
{
    public function indexAction()
    {
        return new Response(__CLASS__ . '::' . __FUNCTION__ . '()');
    }
    
    public function fooAction()
    {
        echo __CLASS__ . '::' . __FUNCTION__ . '()';
    }

    public function barAction()
    {
        if (false === $this->getDI()->get('config')['customKey']) {
            throw new \Exception;
        }

        $this->getDI()->get('stdClass');
        $this->getDI()->get('stdClass2');

        echo __CLASS__ . '::' . __FUNCTION__ . '()';
    }
}
