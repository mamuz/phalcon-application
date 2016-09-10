<?php

namespace StubViewProject\Controller;

use Phalcon\Mvc\Controller;

class Custom extends Controller
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
        $this->view->pick("custom/custom");
    }

    public function noViewAction()
    {
        $this->view->action = __CLASS__ . '::' . __FUNCTION__ . '()';
    }
}
