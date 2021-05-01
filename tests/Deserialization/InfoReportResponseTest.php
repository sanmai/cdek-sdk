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

use CdekSDK\Common\City;
use CdekSDK\Common\Order;
use CdekSDK\Common\Package;
use CdekSDK\Responses\InfoReportResponse;
use Tests\CdekSDK\Fixtures\FixtureLoader;

/**
 * @covers \CdekSDK\Responses\InfoReportResponse
 * @covers \CdekSDK\Common\City
 * @covers \CdekSDK\Common\Package
 * @covers \CdekSDK\Common\Item
 * @covers \CdekSDK\Common\Order
 */
class InfoReportResponseTest extends TestCase
{
    public function test_successful_request()
    {
        $response = $this->getSerializer()->deserialize(FixtureLoader::load('InfoReport.xml'), InfoReportResponse::class, 'xml');

        /** @var $response InfoReportResponse */
        $this->assertInstanceOf(InfoReportResponse::class, $response);

        foreach ($response->getMessages() as $message) {
            if ($message->getErrorCode() !== '') {
                $this->fail($message->getMessage());
            }
        }

        $this->assertCount(1, $response->getOrders());
        $this->assertCount(1, $response);

        foreach ($response->getOrders() as $order) {
            $this->assertSame('TEST-123456', $order->getNumber());
            break;
        }

        foreach ($response as $order) {
            /** @var $order Order */
            $this->assertSame('TEST-123456', $order->getNumber());
            $this->assertSame('101000', $order->getSendCityPostCode());
            $this->assertSame(44, $order->getSenderCity()->getCode());
            $this->assertSame('Москва', $order->getSenderCity()->getName());
            $this->assertSame('Новосибирск', $order->getRecipientCity()->getName());
            $this->assertNull($order->getDateLastChange());

            $this->assertSame('COURIER', $order->getDeliveryVariant());
            $this->assertSame(1, $order->getDeliveryMode());
            $this->assertSame(2231.0, $order->getCashOnDeliv());
            $this->assertSame(2231.0, $order->getCashOnDelivFact());
            $this->assertSame('cashless', $order->getCashOnDelivType());

            $this->assertCount(1, $order->getPackages());

            foreach ($order->getPackages() as $package) {
                /** @var $package Package */
                $this->assertSame('TEST-123456', $package->getBarCode());
                $this->assertSame(1.22, $package->getVolumeWeight());

                $this->assertSame('_', $package->getNumber());
                $this->assertSame(0.5, $package->getWeight());
                $this->assertSame(10, $package->getSizeA());
                $this->assertSame(12, $package->getSizeB());
                $this->assertSame(15, $package->getSizeC());
                $this->assertCount(1, $package->getItems());

                foreach ($package->getItems() as $item) {
                    /** @var $item Item */
                    $this->assertSame('NN0001', $item->getWareKey());
                    $this->assertSame('Example', $item->getMarking());
                    $this->assertSame(500.0, $item->getCost());
                    $this->assertSame(0.0, $item->getPayment());
                    $this->assertSame(0.12, $item->getWeight());
                    $this->assertSame(2, $item->getAmount());
                    $this->assertSame('Test item', $item->getComment());
                    $this->assertSame(0.0, $item->getDelivAmount());
                }
            }

            foreach ($order->getAdditionalServices() as $service) {
                $this->assertSame(2, $service->getServiceCode());
                $this->assertSame(7.5, $service->getSum());
            }

            $city = $order->getSenderCity();
            /** @var $city City */
            $this->assertSame(44, $city->getCode());
            $this->assertSame('101000', $city->getPostCode());
            $this->assertSame('Москва', $city->getName());

            $city = $order->getRecipientCity();
            /** @var $city City */
            $this->assertSame(270, $city->getCode());
            $this->assertSame('630000', $city->getPostCode());
            $this->assertSame('Новосибирск', $city->getName());
        }
    }

    public function test_failing_request()
    {
        $response = $this->getSerializer()->deserialize(FixtureLoader::load('InfoReportFailed.xml'), InfoReportResponse::class, 'xml');

        /** @var $response InfoReportResponse */
        $this->assertInstanceOf(InfoReportResponse::class, $response);

        foreach ($response->getMessages() as $message) {
            $this->assertTrue($message->isError());
            $this->assertStringContainsString('Заказ не найден', $message->getText());
        }
    }

    public function test_date_last_change_without_timezone()
    {
        $response = $this->getSerializer()->deserialize(FixtureLoader::load('InfoReportDateLastChangeYmdHis.xml'), InfoReportResponse::class, 'xml');

        /** @var $response InfoReportResponse */
        $this->assertInstanceOf(InfoReportResponse::class, $response);

        $this->assertCount(1, $response->getOrders());
        $this->assertCount(1, $response);

        foreach ($response->getOrders() as $order) {
            $this->assertSame('ORD-121121', $order->getNumber());
            break;
        }

        foreach ($response as $order) {
            /** @var $order Order */
            $this->assertSame('2019-04-19 19:33:59', $order->getDateLastChange()->format('Y-m-d H:i:s'));
        }
    }

    public function test_it_serializes_to_empty_json()
    {
        $response = new InfoReportResponse();
        $this->assertSame([], $response->jsonSerialize());
    }

    public function test_date_last_change_with_mixed_datetime_format()
    {
        $response = $this->getSerializer()->deserialize(FixtureLoader::load('InfoReportDateLastChangeYmdHisPMixed.xml'), InfoReportResponse::class, 'xml');

        /** @var $response InfoReportResponse */
        $this->assertInstanceOf(InfoReportResponse::class, $response);

        $this->assertCount(3, $response->getOrders());
        $this->assertCount(3, $response);

        $order = $response->getOrders()[0];
        $this->assertSame('ORD-121121', $order->getNumber());
        $this->assertSame('2020-03-05 04:30:00', $order->getDateLastChange()->format('Y-m-d H:i:s'));

        $order = $response->getOrders()[1];
        $this->assertSame('ORD-121122', $order->getNumber());
        $this->assertSame('2020-03-06 05:30:00', $order->getDateLastChange()->format('Y-m-d H:i:s'));

        $order = $response->getOrders()[2];
        $this->assertSame('ORD-121123', $order->getNumber());
        $this->assertSame('2020-03-07 06:30:00+05:00', $order->getDateLastChange()->format('Y-m-d H:i:sP'));
    }
}
