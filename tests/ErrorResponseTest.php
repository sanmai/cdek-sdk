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

use CdekSDK\Responses\ErrorResponse;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

/**
 * @covers \CdekSDK\Responses\ErrorResponse
 */
class ErrorResponseTest extends TestCase
{
    public function test_create()
    {
        $response = ErrorResponse::withHTTPResponse(new class() implements ResponseInterface {
            public function withStatus($code, $reasonPhrase = '')
            {
                return [$code, $reasonPhrase];
            }

            public function hasHeader($name)
            {
                return true;
            }

            public function getHeaders()
            {
                return ['foo' => 'bar'];
            }

            public function getBody()
            {
                return 'foo';
            }

            public function withProtocolVersion($version)
            {
                return $version;
            }

            public function withoutHeader($name)
            {
                return $name;
            }

            public function getHeaderLine($name)
            {
                return $name;
            }

            public function withHeader($name, $value)
            {
                return [$name, $value];
            }

            public function withBody(StreamInterface $body)
            {
                return $body;
            }

            public function getReasonPhrase()
            {
                return 'Bad Gateway Testing 123';
            }

            public function getHeader($name)
            {
                return $name;
            }

            public function getProtocolVersion()
            {
                return 1000;
            }

            public function getStatusCode()
            {
                return 502;
            }

            public function withAddedHeader($name, $value)
            {
                return [$name, $value];
            }
        });

        $this->assertTrue($response->hasErrors());
        $this->assertCount(1, $response->getMessages());
        foreach ($response->getMessages() as $message) {
            $this->assertSame('502', $message->getErrorCode());
            $this->assertSame('Bad Gateway Testing 123', $message->getMessage());
        }

        $this->assertSame(502, $response->getStatusCode());
        $this->assertSame('Bad Gateway Testing 123', $response->getReasonPhrase());
        $this->assertSame(['a', 'b'], $response->withStatus('a', 'b'));
        $this->assertTrue($response->hasHeader(''));
        $this->assertSame(['foo' => 'bar'], $response->getHeaders());
        $this->assertSame('foo', $response->getBody());
        $this->assertSame(100, $response->withProtocolVersion(100));
        $this->assertSame(200, $response->withoutHeader(200));
        $this->assertSame(300, $response->getHeaderLine(300));
        $this->assertSame(['c', 'd'], $response->withHeader('c', 'd'));

        $body = $this->createMock(StreamInterface::class);
        $this->assertSame($body, $response->withBody($body));
        $this->assertSame('Bad Gateway Testing 123', $response->getReasonPhrase());
        $this->assertSame('bar', $response->getHeader('bar'));
        $this->assertSame(1000, $response->getProtocolVersion());
        $this->assertSame(502, $response->getStatusCode());
        $this->assertSame(['e', 'f'], $response->withAddedHeader('e', 'f'));

        return $response;
    }

    /**
     * @depends test_create
     *
     * @param mixed $response
     */
    public function test_it_serializes_to_empty_json($response)
    {
        $this->assertSame([], $response->jsonSerialize());
    }
}
