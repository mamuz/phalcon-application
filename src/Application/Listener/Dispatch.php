<?php
/**
 * The MIT License (MIT)
 *
 * Copyright (c) 2015 Marco Muths
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

namespace Phapp\Application\Listener;

use Phalcon\Dispatcher;
use Phalcon\Events\Event;
use Phalcon\Mvc\View;

class Dispatch
{
    /**
     * @param Event      $event
     * @param Dispatcher $dispatcher
     */
    public function afterExecuteRoute(Event $event, Dispatcher $dispatcher)
    {
        if ($dispatcher->getReturnedValue()) {
            return;
        }

        if ($dispatcher->getNamespaceName() !== $dispatcher->getDefaultNamespace()) {
            /** @var \Phapp\Application\Service\View $view */
            $view = $dispatcher->getDI()->get('view');

            if ($view->isDisabled() || $view->isPicked()) {
                return;
            }

            $viewPathParts = array_diff(
                explode('\\', strtolower($dispatcher->getHandlerClass())),
                explode('\\', strtolower($dispatcher->getDefaultNamespace()))
            );
            $viewPathParts[] = $dispatcher->getActionName();

            $view->pick(implode(DIRECTORY_SEPARATOR, $viewPathParts));
        }
    }

    /**
     * @param Event      $event
     * @param Dispatcher $dispatcher
     */
    public function beforeDispatchLoop(Event $event, Dispatcher $dispatcher)
    {
        if (!$dispatcher->getDI()->has('request')) {
            return;
        }

        /** @var \Phalcon\Http\Request $request */
        $request = $dispatcher->getDI()->get('request');
        if ($request->isAjax()) {
            /** @var \Phapp\Application\Service\View $view */
            $view = $dispatcher->getDI()->get('view');
            $view->setRenderLevel(View::LEVEL_ACTION_VIEW);
        }
    }
}
