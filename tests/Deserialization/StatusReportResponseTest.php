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

use CdekSDK\Common\Item;
use CdekSDK\Common\Order;
use CdekSDK\Common\Package;
use CdekSDK\Common\Reason;
use CdekSDK\Common\State;
use CdekSDK\Common\Status;
use CdekSDK\Responses\StatusReportResponse;
use Tests\CdekSDK\Fixtures\FixtureLoader;

/**
 * @covers \CdekSDK\Responses\StatusReportResponse
 * @covers \CdekSDK\Responses\Types\Message
 * @covers \CdekSDK\Common\Order
 * @covers \CdekSDK\Common\Status
 * @covers \CdekSDK\Common\Reason
 * @covers \CdekSDK\Common\Package
 * @covers \CdekSDK\Common\Item
 */
class StatusReportResponseTest extends TestCase
{
    public function test_it_reads_example_response()
    {
        $response = $this->getSerializer()->deserialize(FixtureLoader::load('StatusReportResponse.xml'), StatusReportResponse::class, 'xml');

        /** @var $response StatusReportResponse */
        $this->assertInstanceOf(StatusReportResponse::class, $response);

        $this->assertSame('2000-12-31', $response->getDateFirst()->format('Y-m-d'));
        $this->assertSame('2018-08-10', $response->getDateLast()->format('Y-m-d'));

        $this->assertCount(2, $response->getOrders());

        $order = $response->getOrders()[0];

        $this->assertSame('1000028000', $order->getDispatchNumber());
        $this->assertSame('2080965069', $order->getNumber());
        $this->assertSame('2018-04-06', $order->DeliveryDate->format('Y-m-d'));
        $this->assertSame('Руслан Альбертович', $order->getRecipientName());

        $this->assertInstanceOf(Reason::class, $order->getDelayReason());
        $this->assertEmpty($order->getDelayReason()->getCode());

        $this->assertInstanceOf(Reason::class, $order->getReason());
        $this->assertEmpty($order->getReason()->getCode());

        $status = $order->getStatus();
        $this->assertInstanceOf(Status::class, $status);

        $this->assertSame('2018-04-06', $status->getDate()->format('Y-m-d'));
        $this->assertSame(4, $status->getCode());
        $this->assertSame('Вручен', $status->getDescription());
        $this->assertSame(1081, $status->getCityCode());
        $this->assertSame('Нальчик', $status->getCityName());

        $states = $status->getStates();
        $firstState = reset($states);

        $this->assertInstanceOf(State::class, $firstState);

        $this->assertSame('2018-03-21', $firstState->Date->format('Y-m-d'));
        $this->assertSame(1, $firstState->Code);
        $this->assertSame('Создан', $firstState->Description);
        $this->assertSame('Москва', $firstState->CityName);
        $this->assertSame(44, $firstState->CityCode);

        $lastState = end($states);

        $this->assertInstanceOf(State::class, $lastState);

        $this->assertSame('2018-04-06', $lastState->Date->format('Y-m-d'));
        $this->assertSame(4, $lastState->Code);
        $this->assertSame('Вручен', $lastState->Description);
        $this->assertSame('Нальчик', $lastState->CityName);
        $this->assertSame(1081, $lastState->CityCode);

        $order = $response->getOrders()[1];
        $this->assertCount(2, $order->Call->CallGood);
        $this->assertCount(1, $order->Call->CallFail);

        $package = $order->getPackages()[0];
        /** @var $package Package */
        $this->assertSame('2066479243', $package->getNumber());
        $this->assertSame('2066479243', $package->getBarCode());

        $item = $package->getItems()[0];
        /** @var $item Item */
        $this->assertSame('2201073352678', $item->getWareKey());
        $this->assertSame(1, $item->getAmount());
        $this->assertSame(0.0, $item->getDelivAmount());
    }

    public function test_it_reads_simple_response()
    {
        $response = $this->getSerializer()->deserialize(FixtureLoader::load('StatusReportMinimal.xml'), StatusReportResponse::class, 'xml');

        /** @var $response StatusReportResponse */
        $this->assertInstanceOf(StatusReportResponse::class, $response);

        $this->assertSame(strtotime('2000-12-31T17:00:00+00:00'), $response->getDateFirst()->getTimestamp());
        $this->assertSame(strtotime('2018-09-01T00:00:00+00:00'), $response->getDateLast()->getTimestamp());

        $this->assertCount(1, $response->getOrders());

        /** @var Order $order */
        $order = $response->getOrders()[0];

        $this->assertInstanceOf(Order::class, $order);
        $this->assertSame('TESTING123', $order->ActNumber);
        $this->assertSame('TEST-123456', $order->getNumber());
        $this->assertSame('1234567', $order->getDispatchNumber());
        $this->assertNull($order->DeliveryDate);
        $this->assertSame('', $order->getRecipientName());

        $this->assertSame(strtotime('2018-09-01T02:10:00+00:00'), $order->getStatus()->getDate()->getTimestamp());
        $this->assertSame(1, $order->getStatus()->getCode());

        $this->assertSame('Создан', $order->getStatus()->getDescription());
        $this->assertSame(44, $order->getStatus()->getCityCode());
        $this->assertSame('Москва', $order->getStatus()->getCityName());

        $this->assertSame(0, $order->getReason()->Code);
        $this->assertSame(0, $order->getDelayReason()->Code);
    }

    public function test_it_reads_failed_response()
    {
        $response = $this->getSerializer()->deserialize(FixtureLoader::load('StatusReportFailed.xml'), StatusReportResponse::class, 'xml');

        /** @var $response StatusReportResponse */
        $this->assertInstanceOf(StatusReportResponse::class, $response);

        $this->assertSame('По указанным параметрам заказов не найдено', $response->getMessage());
        $this->assertSame('ERR_ORDERS_NOT_FOUND', $response->getErrorCode());
    }
}
