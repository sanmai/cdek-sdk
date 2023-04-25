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

namespace Tests\CdekSDK;

use CdekSDK\Contracts\JsonRequest;
use CdekSDK\Contracts\ParamRequest;
use CdekSDK\Contracts\Request;
use CdekSDK\Contracts\XmlRequest;
use CdekSDK\Requests\Concerns\RequestCore;
use PHPUnit\Framework\TestCase;

/**
 * @covers \CdekSDK\Requests\Concerns\RequestCore
 */
class RequestCoreTest extends TestCase
{
    public function test_getters()
    {
        $instance = new class() {
            public const ADDRESS = 'address';
            public const METHOD = 'HEAD';
            public const RESPONSE = RequestCoreTest::class;

            use RequestCore;
        };

        $this->assertSame('address', $instance->getAddress());
        $this->assertSame('HEAD', $instance->getMethod());
        $this->assertSame(RequestCoreTest::class, $instance->getResponseClassName());
    }

    public function test_unrecognized_serialization_format()
    {
        $instance = new class() {
            public const ADDRESS = 'address';
            public const METHOD = 'HEAD';
            public const RESPONSE = RequestCoreTest::class;

            use RequestCore;
        };

        $this->expectException(\BadMethodCallException::class);
        $instance->getSerializationFormat();
    }

    public function test_serialization_xml()
    {
        $instance = new class() implements XmlRequest {
            public const ADDRESS = 'address';
            public const METHOD = 'HEAD';
            public const RESPONSE = RequestCoreTest::class;

            use RequestCore;
        };

        $this->assertSame(Request::SERIALIZATION_XML, $instance->getSerializationFormat());

        $instance = new class() implements ParamRequest {
            public const ADDRESS = 'address';
            public const METHOD = 'HEAD';
            public const RESPONSE = RequestCoreTest::class;

            use RequestCore;

            public function getParams(): array
            {
                return [];
            }
        };

        $this->assertSame(Request::SERIALIZATION_XML, $instance->getSerializationFormat());
    }

    public function test_serialization_json()
    {
        $instance = new class() implements JsonRequest {
            public const ADDRESS = 'address';
            public const METHOD = 'HEAD';
            public const RESPONSE = RequestCoreTest::class;

            use RequestCore;

            public function getBody(): array
            {
                return [];
            }
        };

        $this->assertSame(Request::SERIALIZATION_JSON, $instance->getSerializationFormat());
    }
}
