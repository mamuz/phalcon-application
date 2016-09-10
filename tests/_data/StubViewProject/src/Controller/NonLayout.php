<?php

namespace StubViewProject\Controller;

use Phalcon\Mvc\Controller;

class NonLayout extends Controller
{
    public function indexAction()
    {
        $this->view->action = __CLASS__ . '::' . __FUNCTION__ . '()';
    }
}
