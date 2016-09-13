<?php

namespace StubViewProject\Controller\Admin;

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
}
