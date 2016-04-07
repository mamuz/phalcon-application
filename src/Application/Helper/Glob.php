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

namespace Phapp\Application\Helper;

/**
 * @see https://github.com/zendframework/zend-stdlib/blob/master/src/Glob.php
 */
class Glob
{
    const GLOB_MARK = 0x01;
    const GLOB_NOSORT = 0x02;
    const GLOB_NOCHECK = 0x04;
    const GLOB_NOESCAPE = 0x08;
    const GLOB_BRACE = 0x10;
    const GLOB_ONLYDIR = 0x20;
    const GLOB_ERR = 0x40;

    /**
     * @param  string $pattern
     * @param  int    $flags
     * @return array
     */
    public static function glob($pattern, $flags = 0)
    {
        return static::fallbackGlob($pattern, $flags);
    }

    /**
     * @param  string $pattern
     * @param  int    $flags
     * @return array
     * @throws \RuntimeException
     */
    private static function systemGlob($pattern, $flags)
    {
        if ($flags) {
            $flagMap = array(
                self::GLOB_MARK     => GLOB_MARK,
                self::GLOB_NOSORT   => GLOB_NOSORT,
                self::GLOB_NOCHECK  => GLOB_NOCHECK,
                self::GLOB_NOESCAPE => GLOB_NOESCAPE,
                self::GLOB_BRACE    => GLOB_BRACE,
                self::GLOB_ONLYDIR  => GLOB_ONLYDIR,
                self::GLOB_ERR      => GLOB_ERR,
            );

            $globFlags = 0;

            foreach ($flagMap as $internalFlag => $globFlag) {
                if ($flags & $internalFlag) {
                    $globFlags |= $globFlag;
                }
            }
        } else {
            $globFlags = 0;
        }

        $res = glob($pattern, $globFlags);
        if ($res === false) {
            throw new \RuntimeException("glob('{$pattern}', {$globFlags}) failed"/*, 0, $err*/);
        }
        return $res;
    }

    /**
     * @param  string $pattern
     * @param  int    $flags
     * @return array
     */
    private static function fallbackGlob($pattern, $flags)
    {
        if (!$flags & self::GLOB_BRACE) {
            return static::systemGlob($pattern, $flags);
        }

        $flags &= ~self::GLOB_BRACE;
        $length = strlen($pattern);
        $paths = array();

        if ($flags & self::GLOB_NOESCAPE) {
            $begin = strpos($pattern, '{');
        } else {
            $begin = 0;

            while (true) {
                if ($begin === $length) {
                    $begin = false;
                    break;
                } elseif ($pattern[$begin] === '\\' && ($begin + 1) < $length) {
                    $begin++;
                } elseif ($pattern[$begin] === '{') {
                    break;
                }

                $begin++;
            }
        }

        if ($begin === false) {
            return static::systemGlob($pattern, $flags);
        }

        $next = static::nextBraceSub($pattern, $begin + 1, $flags);

        if ($next === null) {
            return static::systemGlob($pattern, $flags);
        }

        $rest = $next;

        while ($pattern[$rest] !== '}') {
            $rest = static::nextBraceSub($pattern, $rest + 1, $flags);

            if ($rest === null) {
                return static::systemGlob($pattern, $flags);
            }
        }

        $p = $begin + 1;

        while (true) {
            $subPattern = substr($pattern, 0, $begin)
                . substr($pattern, $p, $next - $p)
                . substr($pattern, $rest + 1);

            $result = static::fallbackGlob($subPattern, $flags | self::GLOB_BRACE);

            if ($result) {
                $paths = array_merge($paths, $result);
            }

            if ($pattern[$next] === '}') {
                break;
            }

            $p = $next + 1;
            $next = static::nextBraceSub($pattern, $p, $flags);
        }

        return array_unique($paths);
    }

    /**
     * @param  string $pattern
     * @param  int    $begin
     * @param  int    $flags
     * @return int|null
     */
    private static function nextBraceSub($pattern, $begin, $flags)
    {
        $length = strlen($pattern);
        $depth = 0;
        $current = $begin;

        while ($current < $length) {
            if (!$flags & self::GLOB_NOESCAPE && $pattern[$current] === '\\') {
                if (++$current === $length) {
                    break;
                }

                $current++;
            } else {
                if (($pattern[$current] === '}' && $depth-- === 0) || ($pattern[$current] === ',' && $depth === 0)) {
                    break;
                } elseif ($pattern[$current++] === '{') {
                    $depth++;
                }
            }
        }

        return ($current < $length ? $current : null);
    }
}
