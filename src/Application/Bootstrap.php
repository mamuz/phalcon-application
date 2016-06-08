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

use Phalcon\Cli\Console;
use Phalcon\Mvc\Application;
use Phapp\Application\Service\CliHelper;

class Bootstrap
{
    /** @var array */
    private $config;

    /** @var bool */
    private $isConsole;

    /** @var DependencyInjector */
    private $di;

    /**
     * @param array $config
     * @param bool  $isConsole
     */
    private function __construct(array $config, $isConsole)
    {
        $this->config = $config;
        $this->isConsole = $isConsole;
        $this->di = new DependencyInjector($this->config);
    }

    /**
     * @param array $config
     * @return Bootstrap
     */
    public static function init(array $config = [])
    {
        return new self($config, php_sapi_name() == "cli");
    }

    /**
     * @param array $server
     */
    public function runApplicationOn(array $server)
    {
        if ($this->isConsole) {
            $arguments = CliHelper::extractArgumentsFrom($server['argv']);
            $this->createCliApplication()->handle($arguments);
        } else {
            $response = $this->createMvcApplication()->handle();
            if ($response instanceof \Phalcon\Http\ResponseInterface) {
                $response->send();
            }
        }
    }

    /**
     * @return Application
     */
    private function createMvcApplication()
    {
        return new Application($this->di->createForMvc());
    }

    /**
     * @return Console
     */
    private function createCliApplication()
    {
        return new Console($this->di->createForCli());
    }
}
