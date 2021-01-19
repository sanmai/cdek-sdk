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

namespace Tests\CdekSDK\Deserialization;

use CdekSDK\Contracts\Request;
use CdekSDK\Responses\JsonErrorResponse;
use Tests\CdekSDK\Fixtures\FixtureLoader;

/**
 * @covers \CdekSDK\Responses\JsonErrorResponse
 */
class JsonErrorResponseTest extends TestCase
{
    public function test_it_deserializes()
    {
        $response = $this->getSerializer()->deserialize(FixtureLoader::load('ServiceUnavailableResponse.json'), JsonErrorResponse::class, Request::SERIALIZATION_JSON);

        /** @var $response JsonErrorResponse */
        $this->assertInstanceOf(JsonErrorResponse::class, $response);

        $this->assertTrue($response->hasErrors());

        $this->assertCount(1, $response->getMessages());
        foreach ($response->getMessages() as $error) {
            $this->assertSame('Service Unavailable', $error->getMessage());
            $this->assertSame('503', $error->getErrorCode());
        }
    }

    public function test_it_deserializes_another()
    {
        $response = $this->getSerializer()->deserialize(FixtureLoader::load('JSON_NotFound.json'), JsonErrorResponse::class, Request::SERIALIZATION_JSON);

        /** @var $response JsonErrorResponse */
        $this->assertInstanceOf(JsonErrorResponse::class, $response);

        $this->assertTrue($response->hasErrors());

        $this->assertCount(1, $response->getMessages());
        foreach ($response->getMessages() as $error) {
            $this->assertSame('Not Found', $error->getMessage());
            $this->assertSame('404', $error->getErrorCode());
        }
    }

    public function test_it_serializes_to_empty_json()
    {
        $response = new JsonErrorResponse();
        $this->assertSame([], $response->jsonSerialize());
    }
}
