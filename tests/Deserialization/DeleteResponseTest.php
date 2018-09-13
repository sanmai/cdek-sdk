<?php
/*
 * This code is licensed under the MIT License.
 *
 * Copyright (c) 2018 appwilio <appwilio.com>
 * Copyright (c) 2018 JhaoDa <jhaoda@gmail.com>
 * Copyright (c) 2018 greabock <greabock17@gmail.com>
 * Copyright (c) 2018 Alexey Kopytko <alexey@kopytko.com>
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
 *
 *
 */

declare(strict_types=1);

namespace Tests\Appwilio\CdekSDK\Deserialization;

use Appwilio\CdekSDK\Responses\DeleteResponse;
use Tests\Appwilio\CdekSDK\Fixtures\FixtureLoader;

/**
 * @covers \Appwilio\CdekSDK\Responses\DeleteResponse
 * @covers \Appwilio\CdekSDK\Responses\Types\DeleteRequest
 * @covers \Appwilio\CdekSDK\Common\Order
 * @covers \Appwilio\CdekSDK\Responses\Types\Message
 */
class DeleteResponseTest extends TestCase
{
    public function test_successful_request()
    {
        $response = $this->getSerializer()->deserialize(FixtureLoader::load('DeleteRequestSuccess.xml'), DeleteResponse::class, 'xml');

        /** @var $response DeleteResponse */
        $this->assertInstanceOf(DeleteResponse::class, $response);

        foreach ($response->getMessages() as $message) {
            $this->assertFalse($message->isError());
        }

        foreach ($response->getOrders() as $order) {
            $this->assertSame('TEST-123456', $order->getNumber());
        }
    }

    public function test_failing_request()
    {
        $response = $this->getSerializer()->deserialize(FixtureLoader::load('DeleteRequestFailure.xml'), DeleteResponse::class, 'xml');

        /** @var $response DeleteResponse */
        $this->assertInstanceOf(DeleteResponse::class, $response);

        foreach ($response->getMessages() as $message) {
            $this->assertTrue($message->isError());
        }

        foreach ($response->getOrders() as $order) {
            $this->assertSame('TEST-123456', $order->getNumber());
        }
    }
}
