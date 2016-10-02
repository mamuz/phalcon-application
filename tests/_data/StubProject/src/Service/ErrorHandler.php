<?php

namespace StubProject\Service;

use Phalcon\Di;
use Phalcon\Dispatcher;
use Phalcon\Events\Event;
use Phalcon\Http\Response;
use Phalcon\Mvc;
use Phapp\Application\Service\InjectableInterface;

class ErrorHandler implements InjectableInterface
{
    public static function injectTo(Di $di)
    {
        /** @var Dispatcher $dispatcher */
        $dispatcher = $di->getShared('dispatcher');
        $dispatcher->getEventsManager()->attach(
            'dispatch:beforeException',
            function (Event $event, Dispatcher $dispatcher, \Throwable $e) {
                if ($dispatcher instanceof Mvc\Dispatcher) {
                    $dispatcher->setNamespaceName($dispatcher->getDefaultNamespace());
                    $dispatcher->forward([
                        'controller' => 'error',
                        'action'     => 'index',
                        'params'     => ['exception' => $e],
                    ]);

                    return false;
                }
            }
        );
    }
}
