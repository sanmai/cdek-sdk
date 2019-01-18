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

use CdekSDK\Responses\DeleteResponse;
use Tests\CdekSDK\Fixtures\FixtureLoader;

/**
 * @covers \CdekSDK\Responses\DeleteResponse
 * @covers \CdekSDK\Responses\Types\DeleteRequest
 * @covers \CdekSDK\Common\Order
 */
class DeleteResponseTest extends TestCase
{
    private function loadFixture(string $filename): DeleteResponse
    {
        return $this->getSerializer()->deserialize(FixtureLoader::load($filename), DeleteResponse::class, 'xml');
    }

    public function test_successful_request()
    {
        $response = $this->loadFixture('DeleteRequestSuccess.xml');

        foreach ($response->getMessages() as $message) {
            $this->assertEmpty($message->getErrorCode());
        }

        foreach ($response->getOrders() as $order) {
            $this->assertSame('TEST-123456', $order->getNumber());
        }
    }

    public function test_successful_request2()
    {
        $response = $this->loadFixture('DeleteRequestSuccessOrder.xml');

        $this->assertCount(1, $response->getMessages());

        foreach ($response->getMessages() as $message) {
            $this->assertEmpty($message->getErrorCode());
        }

        $this->assertCount(1, $response->getOrders());

        foreach ($response->getOrders() as $order) {
            $this->assertSame('number-123456', $order->getNumber());
            $this->assertSame('1101110001', $order->getDispatchNumber());
        }
    }

    public function test_failing_request()
    {
        $response = $this->loadFixture('DeleteRequestFailure.xml');

        foreach ($response->getMessages() as $message) {
            break;
        }

        $this->assertTrue(isset($message) && $message->getErrorCode() !== '');

        foreach ($response->getOrders() as $order) {
            $this->assertSame('TEST-123456', $order->getNumber());
        }
    }

    public function test_missing_number()
    {
        $response = $this->loadFixture('DeleteRequestMissingNumber.xml');

        foreach ($response->getMessages() as $message) {
            break;
        }

        $this->assertTrue(isset($message) && $message->getErrorCode() !== '');
    }

    public function test_missing_attribute()
    {
        $response = $this->loadFixture('DeleteRequestNoNumber.xml');

        $this->assertCount(1, $response->getMessages());

        foreach ($response->getMessages() as $message) {
            $this->assertSame('ERR_NEED_ATTRIBUTE', $message->getErrorCode());
        }
    }

    public function test_missing_orders()
    {
        $response = $this->loadFixture('DeleteRequestOrdersMissing.xml');

        foreach ($response->getMessages() as $message) {
            break;
        }

        $this->assertTrue(isset($message) && $message->getErrorCode() !== '');
    }

    public function test_it_serializes_to_empty_json()
    {
        $response = new DeleteResponse();
        $this->assertSame([], $response->jsonSerialize());
    }
}
