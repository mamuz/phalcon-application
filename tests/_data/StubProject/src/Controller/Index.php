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
    
    public function customAction()
    {
        echo __CLASS__ . '::' . __FUNCTION__ . '()';
    }

    public function serviceAction()
    {
        if (false === $this->getDI()->get('config')['customKey']) {
            throw new \Exception;
        }

        $this->getDI()->get('stdClass');
        $this->getDI()->get('stdClass2');

        return __CLASS__ . '::' . __FUNCTION__ . '()';
    }

    public function forwardAction()
    {
        $this->dispatcher->forward(
            array(
                "controller" => "index",
                "action"     => "custom"
            )
        );
    }

    public function exceptionAction()
    {
        throw new \Exception('user error');
    }
}
