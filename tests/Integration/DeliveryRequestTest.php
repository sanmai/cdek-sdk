<?php
/*
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

namespace Tests\CdekSDK\Integration;

use CdekSDK\Common\Address;
use CdekSDK\Common\City;
use CdekSDK\Common\Item;
use CdekSDK\Common\Order;
use CdekSDK\Common\Package;
use CdekSDK\Requests\DeleteRequest;
use CdekSDK\Requests\DeliveryRequest;
use CdekSDK\Requests\PrintLabelsRequest;
use CdekSDK\Requests\PrintReceiptsRequest;
use CdekSDK\Responses\DeleteResponse;
use CdekSDK\Responses\FileResponse;
use CdekSDK\Responses\PrintErrorResponse;

/**
 * @covers \CdekSDK\Requests\DeliveryRequest
 * @covers \CdekSDK\Responses\DeliveryResponse
 * @covers \CdekSDK\Responses\Types\DeliveryRequest
 *
 * @covers \CdekSDK\Requests\DeleteRequest
 * @covers \CdekSDK\Responses\DeleteResponse
 *
 * @covers \CdekSDK\Requests\Templates\PrintRequest
 * @covers \CdekSDK\Requests\PrintReceiptsRequest
 * @covers \CdekSDK\Requests\PrintLabelsRequest
 * @covers \CdekSDK\Responses\PrintErrorResponse
 * @covers \CdekSDK\Responses\FileResponse
 *
 * @group integration
 */
class DeliveryRequestTest extends TestCase
{
    public function test_failing_request()
    {
        $request = new DeliveryRequest();
        $response = $this->getClient()->sendDeliveryRequest($request);

        foreach ($response->getMessages() as $message) {
            $this->assertTrue($message->isError());
            break;
        }
    }

    const TEST_NUMBER = 'TESTING123';

    public function test_delete_success()
    {
        $response = $this->getClient()->sendDeleteRequest(DeleteRequest::create([
            'Number' => self::TEST_NUMBER,
        ])->addOrder(new Order([
            'Number' => 'TEST-123456',
        ])));

        $this->assertInstanceOf(DeleteResponse::class, $response);

        foreach ($response->getMessages() as $message) {
            $this->assertNotEmpty($message->getText());
        }

        foreach ($response->getOrders() as $order) {
            $this->assertSame('TEST-123456', $order->getNumber());
        }
    }

    /**
     * @depends test_delete_success
     */
    public function test_successful_request()
    {
        $order = new Order([
            'Number' => 'TEST-123456',
            'SendCity' => City::create([
                'Code' => 44, // Москва
            ]),
            'RecCity' => City::create([
                'PostCode' => '630001', // Новосибирск
            ]),
            'RecipientName' => 'Иван Петров',
            'RecipientEmail' => 'petrov@test.ru',
            'Phone' => '+7 (383) 202-22-50',
            'TariffTypeCode' => 139, // Посылка дверь-дверь
        ]);

        $order->setAddress(Address::create([
            'Street' => 'Холодильная улица',
            'House' => '16',
            'Flat' => '22',
        ]));

        $order->addPackage(Package::create([
            'Number' => 'TEST-123456',
            'BarCode' => 'TEST-123456',
            'Weight' => 500, // Общий вес (в граммах)
            'SizeA' => 10, // Длина (в сантиметрах), в пределах от 1 до 1500
            'SizeB' => 10,
            'SizeC' => 10,
        ])->addItem(Item::create('NN0001', 500, 0, 120, 2, 'Test item')));

        $request = new DeliveryRequest([
            'Number' => self::TEST_NUMBER,
        ]);
        $request->addOrder($order);

        $response = $this->getClient()->sendDeliveryRequest($request);

        foreach ($response->getMessages() as $message) {
            $this->assertFalse($message->isError());
        }

        foreach ($response->getOrders() as $order) {
            $this->assertSame('TEST-123456', $order->getNumber());
            $this->assertNotEmpty($order->getDispatchNumber());
        }

        return $order->getDispatchNumber();
    }

    /**
     * @depends test_successful_request
     */
    public function test_print_receipts_request(string $dispatchNumber)
    {
        $request = new PrintReceiptsRequest();
        $request->addDispatchNumber($dispatchNumber);

        $response = $this->getClient()->sendPrintReceiptsRequest($request);
        $this->assertInstanceOf(FileResponse::class, $response);

        $this->assertSame('%PDF', $response->getBody()->read(4));
    }

    /**
     * @depends test_successful_request
     */
    public function test_print_labels_request(string $dispatchNumber)
    {
        $request = new PrintLabelsRequest([
            'PrintFormat' => PrintLabelsRequest::PRINT_FORMAT_A5,
        ]);
        $request->addDispatchNumber($dispatchNumber);

        $response = $this->getClient()->sendPrintLabelsRequest($request);
        $this->assertInstanceOf(FileResponse::class, $response);

        $this->assertSame('%PDF', $response->getBody()->read(4));
    }

    public function test_failed_print_receipts_request()
    {
        $request = new PrintReceiptsRequest();
        $request->addOrder(Order::create([
            'DispatchNumber' => 'invalid',
        ]));

        $response = $this->getClient()->sendPrintReceiptsRequest($request);

        $this->assertInstanceOf(PrintErrorResponse::class, $response);

        foreach ($response->getMessages() as $message) {
            $this->assertTrue($message->isError());
        }
    }
}
