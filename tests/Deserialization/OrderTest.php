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

use CdekSDK\Common\Order;
use CdekSDK\Common\Reason;
use CdekSDK\Common\State;
use CdekSDK\Common\Status;
use CdekSDK\Responses\StatusReportResponse;
use Tests\CdekSDK\Fixtures\FixtureLoader;

/**
 * @covers \CdekSDK\Common\Order
 * @covers \CdekSDK\Common\Status
 * @covers \CdekSDK\Common\Reason
 * @covers \CdekSDK\Common\Package
 * @covers \CdekSDK\Common\Item
 */
class OrderTest extends TestCase
{
    private function loadOrder($number): Order
    {
        $response = $this->getSerializer()->deserialize(FixtureLoader::load('StatusReportResponse.xml'), StatusReportResponse::class, 'xml');

        /** @var $response StatusReportResponse */
        return $response->getOrders()[$number];
    }

    public function test_it_reads_another_order()
    {
        $order = $this->loadOrder(0);

        $this->assertSame('1000028000', $order->getDispatchNumber());

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

        /** @var Status $firstState */
        $firstState = reset($states);

        $this->assertInstanceOf(State::class, $firstState);

        $this->assertSame('2018-03-21', $firstState->getDate()->format('Y-m-d'));
        $this->assertSame(1, $firstState->getCode());
        $this->assertSame('Создан', $firstState->getDescription());
        $this->assertSame('Москва', $firstState->getCityName());
        $this->assertSame(44, $firstState->getCityCode());
        $this->assertFalse($firstState->isFinal());

        /** @var Status $lastState */
        $lastState = end($states);

        $this->assertInstanceOf(State::class, $lastState);

        $this->assertSame('2018-04-06', $lastState->getDate()->format('Y-m-d'));
        $this->assertSame(4, $lastState->getCode());
        $this->assertSame('Вручен', $lastState->getDescription());
        $this->assertSame('Нальчик', $lastState->getCityName());
        $this->assertSame(1081, $lastState->getCityCode());
        $this->assertTrue($lastState->isFinal());
    }

    public function test_it_reads_yet_another_order()
    {
        $order = $this->loadOrder(1);

        $this->assertSame('1000356200', $order->getDispatchNumber());

        $this->assertNull($order->getDate());
        $this->assertSame('2066479243', $order->getNumber());
        $this->assertSame('1000356200', $order->getId());
        $this->assertSame(null, $order->getSendCityCode());
        $this->assertSame(null, $order->getSendCityPostCode());
        $this->assertSame(null, $order->getRecCityCode());
        $this->assertSame(null, $order->getRecCityPostCode());
        $this->assertSame('Аркадий Якубович', $order->getRecipientName());
        $this->assertSame(0.0, $order->getDeliveryRecipientCost());
        $this->assertSame(0, $order->getTariffTypeCode());
        $this->assertSame('', $order->getPhone());
        $this->assertSame('', $order->getRecipientEmail());
        $this->assertSame('', $order->getRecipientCurrency());
        $this->assertSame('', $order->getItemsCurrency());
        $this->assertSame('', $order->getComment());
        $this->assertSame('', $order->getSellerName());
        // $this->assertSame(1, $order->getAddress());
        $this->assertCount(0, $order->getAdditionalServices());
        $this->assertCount(0, $order->getScheduleAttempts());
        $this->assertCount(1, $order->getPackages());
        $this->assertCount(0, $order->getCourierCalls());
        $this->assertSame(4, $order->getStatus()->getCode());
        $this->assertSame(20, $order->getReason()->getCode());
        $this->assertSame(0, $order->getDelayReason()->getCode());
        $this->assertCount(1, $order->getDelayReason()->getStates());
        $this->assertCount(0, $order->getAttempts());
        $this->assertSame(0.0, $order->getWeight());
        $this->assertSame(0.0, $order->getDeliverySum());
        // $this->assertSame(1, $order->getDateLastChange());
        $this->assertSame(0.0, $order->getCashOnDeliv());
        $this->assertSame(0.0, $order->getCashOnDelivFact());
        $this->assertSame(0, $order->getDeliveryMode());
        $this->assertSame('', $order->getPvzCode());
        $this->assertSame('', $order->getDeliveryVariant());
        // $this->assertSame(1, $order->getSenderCity());
        // $this->assertSame(1, $order->getRecipientCity());
        $this->assertSame('', $order->getErrorCode());
        $this->assertSame('', $order->getMessage());
        $this->assertCount(2, $order->getCall()->getCallGood());
        // $this->assertSame(1, $order->getReturnOrder());
        $this->assertSame('', $order->getActNumber());
        $this->assertSame('2018-04-07 15:29:32', $order->getDeliveryDate()->format('Y-m-d H:i:s'));
        // $this->assertSame(1, $order->getReturnDispatchNumber());

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

    public function test_reads_pvz_code()
    {
        $order = $this->getSerializer()->deserialize('<Order pvzCode="ABC1234" />', Order::class, 'xml');
        /** @var $order Order */
        $this->assertSame('ABC1234', $order->getPvzCode());

        $order = $this->getSerializer()->deserialize('<Order PvzCode="ABC123" />', Order::class, 'xml');
        /** @var $order Order */
        $this->assertSame('ABC123', $order->getPvzCode());
    }
}
