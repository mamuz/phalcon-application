<?php

namespace StubProject\Task;

use Phalcon\Cli\Task;

class Main extends Task
{
    function mainAction()
    {
        echo __CLASS__ . '::' . __FUNCTION__ . '()';
    }

    function fooAction()
    {
        echo __CLASS__ . '::' . __FUNCTION__ . '()';
    }
    
    function barAction(array $arguments)
    {
        echo __CLASS__ . '::' . __FUNCTION__ . '(' . implode(', ', $arguments). ')';
    }

    function bazAction()
    {
        if (false === $this->getDI()->get('config')['customKey']) {
            throw new \Exception;
        }

        $this->getDI()->get('stdClass');
        $this->getDI()->get('stdClass2');

        echo __CLASS__ . '::' . __FUNCTION__ . '()';
    }
}
