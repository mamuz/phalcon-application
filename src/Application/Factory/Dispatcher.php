<?php
/**
 * The MIT License (MIT)
 *
 * Copyright (c) 2016 Marco Muths
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */

declare(strict_types = 1);

namespace Phapp\Application\Factory;

use Phalcon\Events\Event;
use Phalcon\Events\Manager as EventManager;
use Phalcon\Mvc\Dispatcher as MvcDispatcher;
use Phalcon\Cli\Dispatcher as CliDispatcher;

class Dispatcher
{
    /**
     * @param array $config
     * @return MvcDispatcher
     */
    public static function createMvcFrom(array $config) : MvcDispatcher
    {
        $dispatcher = new MvcDispatcher;
        $dispatcher->setEventsManager(new EventManager);
        $dispatcher->setControllerSuffix(null);
        $dispatcher->setDefaultNamespace($config['controllerDefaultNamespace']);

        $dispatcher->getEventsManager()->attach(
            'dispatch:afterDispatchLoop',
            function (Event $event, MvcDispatcher $dispatcher) {
                if ($dispatcher->getReturnedValue() instanceof \Phalcon\Http\Response) {
                    $dispatcher->getDI()->setShared('response', $dispatcher->getReturnedValue());
                }
            }
        );

        return $dispatcher;
    }

    /**
     * @param array $config
     * @return CliDispatcher
     */
    public static function createCliFrom(array $config) : CliDispatcher
    {
        $dispatcher = new CliDispatcher;
        $dispatcher->setEventsManager(new EventManager);
        $dispatcher->setTaskSuffix(null);
        $dispatcher->setDefaultNamespace($config['taskDefaultNamespace']);

        return $dispatcher;
    }
}
