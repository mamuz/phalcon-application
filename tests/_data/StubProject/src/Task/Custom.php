<?php

namespace StubProject\Task;

use Phalcon\Cli\Task;

class Custom extends Task
{
    function mainAction()
    {
        echo __CLASS__ . '::' . __FUNCTION__ . '()';
    }

    function customAction()
    {
        echo __CLASS__ . '::' . __FUNCTION__ . '()';
    }
    
    function argumentAction(array $arguments)
    {
        echo __CLASS__ . '::' . __FUNCTION__ . '(' . implode(', ', $arguments). ')';
    }

    function serviceAction()
    {
        if (false === $this->getDI()->get('config')['customKey']) {
            throw new \Exception;
        }
        
        $this->getDI()->get('stdClass');
        $this->getDI()->get('stdClass2');

        echo __CLASS__ . '::' . __FUNCTION__ . '()';
    }
}