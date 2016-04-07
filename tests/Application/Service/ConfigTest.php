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

namespace PhappTest\Application\Service;

use Phapp\Application\Service\Config;

class ConfigTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider dataProvider
     * @param string $env
     * @param array  $expected
     */
    public function testReadingWithNativeGlob($env, $expected)
    {
        $pathPattern = sprintf(__DIR__ . '/stub/config/{,*.}{global,%s,local}.php', $env);
        $configService = new Config($pathPattern);
        $this->assertEquals($expected, $configService->read());
    }

    /**
     * @dataProvider dataProvider
     * @param string $env
     * @param array  $expected
     */
    public function testReadingWithFallbackGlob($env, $expected)
    {
        $pathPattern = sprintf(__DIR__ . '/stub/config/{,*.}{global,%s,local}.php', $env);
        $configService = new Config($pathPattern, null, true);
        $this->assertEquals($expected, $configService->read());
    }

    /**
     * @return array
     */
    public function dataProvider()
    {
        return array(
            ['development', [
                'services' =>
                    [
                        'logger'       => 'devLogger',
                        'errorHandler' => 'globalErrorHandler',
                        'foo'          => [
                            'id' => 'local',
                        ],
                        'bar'          => [
                            'id' => 'local',
                        ],
                        'baz'          => [
                            'id' => 'local',
                        ],
                    ],
            ]],
            ['integration', [
                'services' =>
                    [
                        'logger'       => 'integratationLogger',
                        'errorHandler' => 'globalErrorHandler',
                        'foo'          => [
                            'id' => 'local',
                        ],
                        'bar'          => [
                            'id' => 'local',
                        ],
                        'baz'          => [
                            'id' => 'local',
                        ],
                    ],
            ]],
            ['production', [
                'services' =>
                    [
                        'logger'       => 'productionLogger',
                        'errorHandler' => 'globalErrorHandler',
                        'foo'          => [
                            'id' => 'local',
                        ],
                        'bar'          => [
                            'id' => 'local',
                        ],
                        'baz'          => [
                            'id' => 'local',
                        ],
                    ],
            ]],
        );
    }
}
