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

use CdekSDK\Responses\UpdateResponse;
use Tests\CdekSDK\Fixtures\FixtureLoader;

/**
 * @covers \CdekSDK\Responses\UpdateResponse
 */
class UpdateResponseTest extends TestCase
{
    public function test_successful_request()
    {
        $response = $this->getSerializer()->deserialize(FixtureLoader::load('UpdateResponse.xml'), UpdateResponse::class, 'xml');

        /** @var $response UpdateResponse */
        $this->assertInstanceOf(UpdateResponse::class, $response);

        $this->assertCount(1, $response->getMessages());

        foreach ($response->getMessages() as $message) {
            $this->assertFalse($message->isError());
        }

        $this->assertCount(1, $response->getOrders());

        foreach ($response->getOrders() as $order) {
            $this->assertSame('45645600', $order->getNumber());
            $this->assertSame('123123000', $order->getDispatchNumber());
        }
    }

    public function test_failed_request()
    {
        $response = $this->getSerializer()->deserialize(FixtureLoader::load('UpdateResponseError.xml'), UpdateResponse::class, 'xml');

        /** @var $response UpdateResponse */
        $this->assertInstanceOf(UpdateResponse::class, $response);

        $this->assertCount(1, $response->getMessages());

        foreach ($response->getMessages() as $message) {
            $this->assertTrue($message->isError());
            $this->assertSame('ERR_NEED_ATTRIBUTE', $message->getErrorCode());
            break;
        }

        $this->assertSame('123456', $response->getTraceId());
    }

    public function test_it_serializes_to_empty_json()
    {
        $response = new UpdateResponse();
        $this->assertSame([], $response->jsonSerialize());
    }
}
