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

namespace Phapp\Application;

use Phalcon\Config;
use Phalcon\Di;
use Phapp\Application\Factory\DispatchEventsManager;
use Phapp\Application\Service\InjectableInterface;

class DependencyInjector
{
    /** @var array */
    private $config;

    /**
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config = $config;
    }

    /**
     * @return Di
     */
    public function createForMvc(): Di
    {
        $di = new Di\FactoryDefault;

        $this->injectConfigTo($di);

        $config = $this->config;
        $di->setShared('router', function () use ($config) {
            $routes = isset($config['routes']) ? $config['routes'] : array();
            return Factory\Router::createFrom($routes);
        });

        $di->setShared('view', function () use ($config) {
            $view = new Service\View;
            if (isset($config['view']['templatePath'])) {
                $view->setViewsDir($config['view']['templatePath']);
            }
            return $view;
        });

        /** @var \Phalcon\Mvc\Dispatcher $dispatcher */
        $dispatcher = $di->get('dispatcher');
        $dispatcher->setEventsManager(DispatchEventsManager::create());
        $dispatcher->setControllerSuffix(null);
        $dispatcher->setDefaultNamespace($this->config['dispatcher']['controllerDefaultNamespace']);

        $this->injectServicesTo($di);

        return $di;
    }

    /**
     * @return Di
     */
    public function createForCli(): Di
    {
        $di = new Di\FactoryDefault\Cli;

        $this->injectConfigTo($di);

        /** @var \Phalcon\Cli\Dispatcher $dispatcher */
        $dispatcher = $di->get('dispatcher');
        $dispatcher->setEventsManager(DispatchEventsManager::create());
        $dispatcher->setTaskSuffix(null);
        $dispatcher->setDefaultNamespace($this->config['dispatcher']['taskDefaultNamespace']);

        $this->injectServicesTo($di);

        return $di;
    }

    /**
     * @param Di $di
     */
    private function injectConfigTo(Di $di)
    {
        $config = $this->config;
        $di->set('config', function () use ($config) {
            return new Config($config);
        });
    }

    /**
     * @param Di $di
     */
    private function injectServicesTo(Di $di)
    {
        /** @var InjectableInterface[] $services */
        $services = isset($this->config['services']) ? $this->config['services'] : array();
        foreach ($services as $service) {
            $service::injectTo($di);
        }
    }
}
