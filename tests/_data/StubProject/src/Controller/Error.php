<?php

namespace StubProject\Controller;

use Phalcon\Mvc\Controller;
use Phalcon\Http\Response;

class Error extends Controller
{
    public function indexAction()
    {
        if (!$this->dispatcher->wasForwarded()
            || $this->dispatcher->getParam('exception') instanceof \Phalcon\Mvc\Dispatcher\Exception
        ) {
            return new Response("NotFound", 404, "Not Found");
        }

        return new Response("Error", 500, "Internal Server Error");
    }
}
