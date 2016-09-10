<?php

namespace StubProject\Service;

use Phalcon\Di;
use Phapp\Application\Service\InjectableInterface;

class StdClass2 implements InjectableInterface
{
    public static function injectTo(Di $di)
    {
        $di->setShared(
            'stdClass2',
            function () use ($di) {
                return new \stdClass;
            }
        );
    }
}
