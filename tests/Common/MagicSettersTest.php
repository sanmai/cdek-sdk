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

namespace Tests\CdekSDK\Common;

use CdekSDK\Requests\Concerns\MagicSetters;

/**
 * @covers \CdekSDK\Requests\Concerns\MagicSetters
 */
class MagicSettersTest extends TestCase
{
    public function test_can_set_params()
    {
        $example = new class() {
            use MagicSetters;

            public $foo;
            public $bar;
            public $justTesting;
        };

        $example->setFoo('foo');
        $example = $example->setBar('bar');
        $example = $example->setJustTesting(123);

        $this->assertSame([
            'foo'         => 'foo',
            'bar'         => 'bar',
            'justTesting' => 123,
        ], get_object_vars($example));
    }

    public function test_missing_setter()
    {
        $example = new class() {
            use MagicSetters;

            private $foo;
        };

        $example->setFoo('foo');

        $this->expectException(\BadMethodCallException::class);
        $example->setBar('bar');
    }
}
