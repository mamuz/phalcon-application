<?php

namespace StubProject\Task;

use Phalcon\Cli\Task;

/**
 * @property \Phalcon\Cli\Dispatcher $dispatcher
 */
class Custom extends Task
{
    public function mainAction()
    {
        echo __CLASS__ . '::' . __FUNCTION__ . '()';
    }

    public function customAction()
    {
        echo __CLASS__ . '::' . __FUNCTION__ . '()';
    }

    public function argumentAction(array $arguments)
    {
        echo __CLASS__ . '::' . __FUNCTION__ . '(' . implode(', ', $arguments) . ')';
    }

    public function optionAction(array $arguments)
    {
        $opts = [];
        foreach ($this->dispatcher->getOptions() as $key => $value) {
            $opts[] = '--' . $key . '=' . $value;
        }
        echo __CLASS__ . '::' . __FUNCTION__ . '(' . implode(', ', $arguments) . ', '. implode(', ', $opts). ')';
    }

    public function serviceAction()
    {
        if (false === $this->getDI()->get('config')['customKey']) {
            throw new \Exception;
        }
        
        $this->getDI()->get('stdClass');
        $this->getDI()->get('stdClass2');

        echo __CLASS__ . '::' . __FUNCTION__ . '()';
    }
}
