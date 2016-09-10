<?php

namespace StubProject\Service;

use Phalcon\Di;
use Phapp\Application\Service\InjectableInterface;

class StdClass implements InjectableInterface
{
    public static function injectTo(Di $di)
    {
        $di->setShared(
            'stdClass',
            function () use ($di) {
                return new \stdClass;
            }
        );
    }
}
