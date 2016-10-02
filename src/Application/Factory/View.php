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

use Phalcon\DiInterface;
use Phalcon\Events\Event;
use Phalcon\Mvc\Dispatcher as MvcDispatcher;
use Phalcon\Mvc\View as MvcView;

class View
{
    /**
     * @param array       $config
     * @param DiInterface $di
     * @return MvcView
     */
    public static function createFrom(array $config, DiInterface $di) : MvcView
    {
        $view = new MvcView;

        if (isset($config['templatePath'])) {
            $view->setViewsDir($config['templatePath']);
            /** @var MvcDispatcher $dispatcher */
            $dispatcher = $di->getShared('dispatcher');
            $dispatcher->getEventsManager()->attach('dispatch', new self);
        } else {
            $view->disable();
        }

        return $view;
    }

    /**
     * @param Event         $event
     * @param MvcDispatcher $dispatcher
     */
    public function beforeExecuteRoute(Event $event, MvcDispatcher $dispatcher)
    {
        if ($dispatcher->getNamespaceName() !== $dispatcher->getDefaultNamespace()) {
            /** @var MvcView $view */
            $view = $dispatcher->getDI()->get('view');

            if ($view->isDisabled()) {
                return;
            }

            $viewPathParts = array_values(array_diff(
                explode('\\', strtolower($dispatcher->getHandlerClass())),
                explode('\\', strtolower($dispatcher->getDefaultNamespace()))
            ));
            $viewPathParts[] = $dispatcher->getActionName();

            $view->setLayout($viewPathParts[0]);
            $view->pick(implode(DIRECTORY_SEPARATOR, $viewPathParts));
        }
    }

    /**
     * @param Event         $event
     * @param MvcDispatcher $dispatcher
     */
    public function beforeDispatchLoop(Event $event, MvcDispatcher $dispatcher)
    {
        if (!$dispatcher->getDI()->has('request')) {
            return;
        }

        /** @var \Phalcon\Http\Request $request */
        $request = $dispatcher->getDI()->get('request');
        if ($request->isAjax()) {
            /** @var MvcView $view */
            $view = $dispatcher->getDI()->get('view');
            $view->setRenderLevel(MvcView::LEVEL_ACTION_VIEW);
        }
    }
}
