<?php
/**
 * This code is licensed under the MIT License.
 *
 * Copyright (c) 2018 Appwilio (http://appwilio.com), greabock (https://github.com/greabock), JhaoDa (https://github.com/jhaoda)
 * Copyright (c) 2018 Alexey Kopytko <alexey@kopytko.com> and contributors
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

declare(strict_types=1);

namespace Tests\CdekSDK\Integration;

use Psr\Log\LoggerInterface;
use Psr\Log\LoggerTrait;

final class DebuggingLogger implements LoggerInterface
{
    use LoggerTrait;

    /**
     * @param mixed  $level
     * @param string $message
     * @param array  $context
     *
     * @psalm-suppress MixedTypeCoercion
     */
    public function log($level, $message, array $context = [])
    {
        if ($context) {
            $message = \strtr($message, \iterator_to_array(self::context2replacements($context), true));
        }

        /*
         * Если нужна запись в файл, то нужно скопировать этот файл себе в проект и заменить строку выше строкой ниже.
         * Лог будет записан в cdek-requests.log в корне проекта.
         */

        \fwrite(\STDERR, "\n{$message}\n\n");
        //fwrite($this->getLogFileHandle(), "\n{$message}\n\n");
    }

    /**
     * Возвращает указатель на открытый файл cdek-requests.log в корне проекта.
     *
     * @return resource
     */
    private static function getLogFileHandle()
    {
        static $fh;

        if (!$fh) {
            $reflection = new \ReflectionClass(\Composer\Autoload\ClassLoader::class);
            $fh = \fopen(\dirname((string) $reflection->getFileName(), 3).DIRECTORY_SEPARATOR.'cdek-requests.log', 'a');
        }

        \assert(\is_resource($fh));

        return $fh;
    }

    /**
     * @param array<string, string> $context
     *
     * @return \Generator
     */
    private static function context2replacements($context): \Generator
    {
        foreach ($context as $key => $value) {
            yield '{'.$key.'}' => $value;
        }
    }
}
