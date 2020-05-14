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

use CdekSDK\Responses\DeliveryResponse;
use CdekSDK\Responses\Types\DeliveryRequest;
use Tests\CdekSDK\Fixtures\FixtureLoader;

/**
 * @covers \CdekSDK\Responses\DeliveryResponse
 * @covers \CdekSDK\Responses\Types\DeliveryRequest
 */
class DeliveryResponseTest extends TestCase
{
    public function test_failing_request()
    {
        $response = $this->getSerializer()->deserialize(FixtureLoader::load('DeliveryRequestFailed.xml'), DeliveryResponse::class, 'xml');

        /** @var $response DeliveryResponse */
        $this->assertInstanceOf(DeliveryResponse::class, $response);

        $this->assertCount(2, $response->getMessages());

        foreach ($response->getMessages() as $message) {
            $this->assertTrue($message->isError());
        }
    }

    public function test_duplicate_request()
    {
        $response = $this->getSerializer()->deserialize(FixtureLoader::load('DeliveryRequestDuplicate.xml'), DeliveryResponse::class, 'xml');

        /** @var $response DeliveryResponse */
        $this->assertInstanceOf(DeliveryResponse::class, $response);

        $this->assertCount(2, $response->getMessages());

        foreach ($response->getMessages() as $message) {
            $this->assertTrue($message->isError());
            break;
        }
    }

    public function test_failed_orders()
    {
        $response = $this->getSerializer()->deserialize(FixtureLoader::load('DeliveryRequestErrors.xml'), DeliveryResponse::class, 'xml');

        /** @var $response DeliveryResponse */
        $this->assertInstanceOf(DeliveryResponse::class, $response);

        $this->assertCount(3, $response->getMessages());

        foreach ($response->getMessages() as $message) {
            $this->assertTrue($message->isError());
            break;
        }

        $this->assertCount(2, $response->getErrors());

        foreach ($response->getErrors() as $order) {
            $this->assertSame('Отсутствие обязательного атрибута: PVZCODE', $order->getMessage());
            $this->assertSame('ERR_NEED_ATTRIBUTE', $order->getErrorCode());
            $this->assertSame('123456', $order->getNumber());
            break;
        }

        $order = \iterator_to_array($response->getErrors(), false)[1];

        $this->assertSame('Почтовый индекс города получателя отсутствует в базе СДЭК: RecCityPostCode=999999', $order->getMessage());
        $this->assertSame('ERR_RECCITYPOSTCODE', $order->getErrorCode());
        $this->assertSame('234567', $order->getNumber());

        $this->assertCount(1, $response->getOrders());

        foreach ($response->getOrders() as $order) {
            $this->assertSame('TEST-123456', $order->getNumber());
            $this->assertSame('100001234', $order->getDispatchNumber());
            break;
        }
    }

    public function test_failing_request_with_failed_auth()
    {
        $response = $this->getSerializer()->deserialize(FixtureLoader::load('DeliveryRequestFailedAuth.xml'), DeliveryResponse::class, 'xml');

        /** @var $response DeliveryResponse */
        $this->assertInstanceOf(DeliveryResponse::class, $response);

        $this->assertCount(1, $response->getMessages());

        foreach ($response->getMessages() as $message) {
            $this->assertTrue($message->isError());
        }

        $this->assertCount(1, $response->getRequests());

        foreach ($response->getRequests() as $request) {
            $this->assertInstanceOf(DeliveryRequest::class, $request);
            $this->assertSame('TESTING123', $request->getNumber());
        }
    }

    public function test_successful_request()
    {
        $response = $this->getSerializer()->deserialize(FixtureLoader::load('DeliveryRequestSuccess.xml'), DeliveryResponse::class, 'xml');

        /** @var $response DeliveryResponse */
        $this->assertInstanceOf(DeliveryResponse::class, $response);

        foreach ($response->getMessages() as $message) {
            $this->assertFalse($message->isError());
        }

        $this->assertTrue(isset($message));

        foreach ($response->getOrders() as $order) {
            $this->assertSame('TEST-123456', $order->getNumber());
            $this->assertSame('100001234', $order->getDispatchNumber());
        }

        $this->assertTrue(isset($order));
    }

    public function test_missing_courier_call()
    {
        $response = $this->getSerializer()->deserialize(FixtureLoader::load('DeliveryRequestMissingCall.xml'), DeliveryResponse::class, 'xml');

        /** @var $response DeliveryResponse */
        $this->assertInstanceOf(DeliveryResponse::class, $response);

        $this->assertCount(3, $response->getMessages());

        foreach ($response->getMessages() as $message) {
            $this->assertSame('ERROR_CALL_EMPTY', $message->getErrorCode());
            break;
        }
    }

    public function test_successful_request_with_call()
    {
        $response = $this->getSerializer()->deserialize(FixtureLoader::load('DeliveryRequestWithCallSuccess.xml'), DeliveryResponse::class, 'xml');

        /** @var $response DeliveryResponse */
        $this->assertInstanceOf(DeliveryResponse::class, $response);

        $this->assertCount(1, $response->getOrders());

        foreach ($response->getOrders() as $order) {
            $this->assertSame('TESTING-123', $order->getNumber());
            $this->assertSame('110000123', $order->getDispatchNumber());
        }

        $this->assertCount(1, $response->getMessages());
        $this->assertCount(1, $response->getCalls());

        foreach ($response->getCalls() as $call) {
            $this->assertSame('22022033', $call->getNumber());
        }

        $this->assertSame('e5341942156970a92', $response->getTraceId());
    }

    public function test_internal_error()
    {
        $response = $this->getSerializer()->deserialize(FixtureLoader::load('DeliveryRequestInternalError.xml'), DeliveryResponse::class, 'xml');

        /** @var $response DeliveryResponse */
        $this->assertInstanceOf(DeliveryResponse::class, $response);

        $this->assertTrue($response->hasErrors());
        $this->assertCount(1, $response->getMessages());

        foreach ($response->getMessages() as $message) {
            $this->assertSame('error_internal', $message->getErrorCode());
            break;
        }

        $this->assertSame('123456abcdef', $response->getTraceId());
    }

    public function test_error_without_order_number()
    {
        $response = $this->getSerializer()->deserialize(FixtureLoader::load('DeliveryRequestNoOrderNumberError.xml'), DeliveryResponse::class, 'xml');

        /** @var $response DeliveryResponse */
        $this->assertInstanceOf(DeliveryResponse::class, $response);

        $this->assertTrue($response->hasErrors());
        $this->assertCount(1, $response->getMessages());

        foreach ($response->getMessages() as $message) {
            $this->assertSame('ERR_NEED_ATTRIBUTE', $message->getErrorCode());
            break;
        }

        $this->assertSame('123123132', $response->getTraceId());
    }

    public function test_it_serializes_to_empty_json()
    {
        $response = new DeliveryResponse();
        $this->assertSame([], $response->jsonSerialize());
    }
}
