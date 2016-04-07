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

namespace Phapp\Application\Service;

use Phapp\Application\Helper\Glob;
use Phalcon\Config as Container;

class Config
{
    /** @var string | null */
    private $cacheFile;

    /** @var string */
    private $configGlobPath;

    /** @var bool */
    private $useFallbackGlob;

    /**
     * @param string        $pathPattern
     * @param string | null $cacheFile
     * @param bool          $useFallbackGlob
     */
    public function __construct($pathPattern, $cacheFile = null, $useFallbackGlob = false)
    {
        $this->configGlobPath = $pathPattern;
        $this->cacheFile = $cacheFile;
        $this->useFallbackGlob = $useFallbackGlob || !defined('GLOB_BRACE');
    }

    /**
     * @return array
     */
    public function read()
    {
        if ($this->isCached()) {
            return $this->cachedConfig();
        }

        $config = array();

        if ($this->useFallbackGlob) {
            foreach (Glob::glob($this->configGlobPath, Glob::GLOB_BRACE) as $file) {
                $config = array_replace_recursive($config, require $file);
            }
        } else {
            foreach (glob($this->configGlobPath, GLOB_BRACE) as $file) {
                $config = array_replace_recursive($config, require $file);
            }
        }

        if ($this->isCachable()) {
            $this->writeCacheWith($config);
        }

        return $config;
    }

    /**
     * @return bool
     */
    private function isCached()
    {
        return $this->isCachable() && file_exists($this->cacheFile);
    }

    /**
     * @return bool
     */
    private function isCachable()
    {
        return is_string($this->cacheFile);
    }

    /**
     * @return array
     */
    private function cachedConfig()
    {
        return include $this->cacheFile;
    }

    /**
     * @param array $config
     */
    private function writeCacheWith(array $config)
    {
        $content = "<?php\nreturn " . var_export($config, 1) . ';';
        file_put_contents($this->cacheFile, $content);
    }
}

