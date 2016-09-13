<?php

namespace StubViewProject\Controller;

use Phalcon\Http\Response;
use Phalcon\Mvc\Controller;

class Index extends Controller
{
    public function indexAction()
    {
        $this->view->action = __CLASS__ . '::' . __FUNCTION__ . '()';
    }
    
    public function customAction()
    {
        $this->view->action = __CLASS__ . '::' . __FUNCTION__ . '()';
    }

    public function pickAction()
    {
        $this->view->action = __CLASS__ . '::' . __FUNCTION__ . '()';
        $this->view->pick("index/custom");
    }
    
    public function noViewAction()
    {
        $this->view->action = __CLASS__ . '::' . __FUNCTION__ . '()';
    }

    public function serviceAction()
    {
        $this->getDI()->get('stdClass');
        $this->view->action = __CLASS__ . '::' . __FUNCTION__ . '()';
    }

    public function returnAction()
    {
        return  __CLASS__ . '::' . __FUNCTION__ . '()';
    }

    public function responseAction()
    {
        return  new Response(__CLASS__ . '::' . __FUNCTION__ . '()');
    }

    public function echoAction()
    {
        echo  __CLASS__ . '::' . __FUNCTION__ . '()';
    }
}
