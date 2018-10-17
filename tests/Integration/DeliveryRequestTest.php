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

namespace Tests\CdekSDK\Integration;

use CdekSDK\Common\AdditionalService;
use CdekSDK\Common\Address;
use CdekSDK\Common\CallCourier;
use CdekSDK\Common\ChangePeriod;
use CdekSDK\Common\City;
use CdekSDK\Common\Item;
use CdekSDK\Common\Order;
use CdekSDK\Common\Package;
use CdekSDK\Common\Sender;
use CdekSDK\Requests\AddDeliveryRequest;
use CdekSDK\Requests\CallCourierRequest;
use CdekSDK\Requests\DeleteRequest;
use CdekSDK\Requests\DeliveryRequest;
use CdekSDK\Requests\InfoReportRequest;
use CdekSDK\Requests\PrintLabelsRequest;
use CdekSDK\Requests\PrintReceiptsRequest;
use CdekSDK\Requests\StatusReportRequest;
use CdekSDK\Responses\CallCourierResponse;
use CdekSDK\Responses\DeleteResponse;
use CdekSDK\Responses\FileResponse;
use CdekSDK\Responses\InfoReportResponse;
use CdekSDK\Responses\StatusReportResponse;

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
 * @covers \CdekSDK\Requests\StatusReportRequest
 * @covers \CdekSDK\Responses\StatusReportResponse
 *
 * @covers \CdekSDK\Requests\InfoReportRequest
 * @covers \CdekSDK\Responses\InfoReportResponse
 *
 * @covers \CdekSDK\Requests\CallCourierRequest
 * @covers \CdekSDK\Responses\CallCourierResponse
 *
 * @group integration
 */
class DeliveryRequestTest extends TestCase
{
    const TEST_NUMBER = 'TESTING123';

    public function test_delete_success()
    {
        $response = $this->getClient()->sendDeleteRequest(DeleteRequest::create([
            'Number' => self::TEST_NUMBER,
        ])->addOrder(new Order([
            'Number' => 'TEST-123456',
        ])));

        foreach ($response->getMessages() as $message) {
            $this->assertEmpty($message->getErrorCode(), $message->getMessage());
        }

        $this->assertInstanceOf(DeleteResponse::class, $response);

        foreach ($response->getMessages() as $message) {
            $this->assertNotEmpty($message->getMessage());
        }

        foreach ($response->getOrders() as $order) {
            $this->assertSame('TEST-123456', $order->getNumber());
        }
    }

    /**
     * @depends test_delete_success
     */
    public function test_failing_request_with_demo_keys()
    {
        $order = new Order([
            'Number'   => 'TEST-123456',
            'SendCity' => City::create([
                'Code' => 44, // Москва
            ]),
            'RecCity' => City::create([
                'PostCode' => '630001', // Новосибирск
            ]),
            'RecipientName'  => 'Иван Петров',
            'RecipientEmail' => 'petrov@test.ru',
            'Phone'          => '+7 (383) 202-22-50',
            'TariffTypeCode' => 139, // Посылка дверь-дверь
        ]);

        $order->setAddress(Address::create([
            'Street' => 'Холодильная улица',
            'House'  => '16',
            'Flat'   => '22',
        ]));

        $package = Package::create([
            'Number'  => 'TEST-123456',
            'BarCode' => 'TEST-123456',
            'Weight'  => 500, // Общий вес (в граммах)
            'SizeA'   => 10, // Длина (в сантиметрах), в пределах от 1 до 1500
            'SizeB'   => 10,
            'SizeC'   => 10,
        ]);

        $package->addItem(new Item([
            'WareKey' => 'NN0001', // Идентификатор/артикул товара/вложения
            'Cost'    => 500, // Объявленная стоимость товара (за единицу товара)
            'Payment' => 0, // Оплата за товар при получении (за единицу товара)
            'Weight'  => 120, // Вес (за единицу товара, в граммах)
            'Amount'  => 2, // Количество единиц одноименного товара (в штуках)
            'Comment' => 'Test item',
        ]));

        $order->addPackage($package);

        $request = new DeliveryRequest([
            'Number' => self::TEST_NUMBER,
        ]);
        $request->addOrder($order);

        $response = $this->getClient()->sendDeliveryRequest($request);

        $this->skipIfKnownAPIErrorCode($response);

        foreach ($response->getMessages() as $message) {
            if ($message->getErrorCode() === 'ERR_AUTH') {
                $this->fail("Access keys require an update; received {$message->getErrorCode()}: {$message->getMessage()}");
            }
        }

        $this->assertTrue($response->hasErrors());
        $this->assertCount(2, $response->getMessages());

        foreach ($response->getMessages() as $message) {
            $this->assertNotEmpty($message->getErrorCode(), $message->getMessage());
            $this->assertSame('ERR_INVALID_TARIFF_WITH_CLIENT', $message->getErrorCode());
            break;
        }
    }

    /**
     * @depends test_delete_success
     */
    public function test_successful_request()
    {
        $order = new Order([
            'ClientSide' => Order::CLIENT_SIDE_SENDER,
            'Number'     => 'TEST-123456',
            'SendCity'   => City::create([
                'Code' => 44, // Москва
            ]),
            'RecCity' => City::create([
                'PostCode' => '630001', // Новосибирск
            ]),
            'RecipientName'    => 'Иван Петров',
            'RecipientEmail'   => 'petrov@test.ru',
            'Phone'            => '+7 (383) 202-22-50',
            'TariffTypeCode'   => 1,
            'RecipientCompany' => 'Петров и партнёры, ООО',
            'Comment'          => 'Это тестовый заказ',
        ]);

        $order->setSender(Sender::create([
            'Company' => 'ЗАО «Рога и Копыта»',
            'Name'    => 'Петр Иванов',
            'Phone'   => '+7 (495) 101-11-20',
        ])->setAddress(Address::create([
            'Street' => 'Морозильная улица',
            'House'  => '2',
            'Flat'   => '101',
        ])));

        $order->setAddress(Address::create([
            'Street'  => 'Холодильная улица',
            'House'   => '16',
            'Flat'    => '22',
        ]));

        $package = Package::create([
            'Number'  => 'TEST-123456',
            'BarCode' => 'TEST-123456',
            'Weight'  => 500, // Общий вес (в граммах)
            'SizeA'   => 10, // Длина (в сантиметрах), в пределах от 1 до 1500
            'SizeB'   => 10,
            'SizeC'   => 10,
            'Comment' => 'Обязательное описание вложения',
        ]);

        $order->addPackage($package);

        $order->addService(AdditionalService::create(AdditionalService::SERVICE_DANGEROUS_GOODS));

        $request = new AddDeliveryRequest([
            'Number'          => self::TEST_NUMBER,
            'ForeignDelivery' => false,
            'Currency'        => 'RUB',
        ]);
        $request->addOrder($order);

        $response = $this->getClient()->sendAddDeliveryRequest($request);

        $this->skipIfKnownAPIErrorCode($response);

        foreach ($response->getMessages() as $message) {
            $this->assertEmpty($message->getErrorCode(), $message->getMessage());
        }

        $this->assertFalse($response->hasErrors());

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
        $request->addOrder(Order::withDispatchNumber($dispatchNumber));

        $response = $this->getClient()->sendPrintReceiptsRequest($request);

        if ($response->hasErrors()) {
            foreach ($response->getMessages() as $message) {
                if ($message->getErrorCode() === 'ERR_API') {
                    $this->markTestSkipped($message->getMessage());
                }

                // Новые заказы попадают в БД СДЭК с задержкой, потому квитанцию не всегда получается сразу получить
                if ($message->getErrorCode() === 'ERR_INVALID_DISPATCHNUMBER') {
                    $this->assertContains($dispatchNumber, $message->getMessage());
                    $this->markTestSkipped($message->getMessage());
                }

                if ($message->getErrorCode()) {
                    $this->fail($message->getMessage());
                }
            }
        }

        $this->assertFalse($response->hasErrors());
        $this->assertInstanceOf(FileResponse::class, $response);

        $this->assertSame('%PDF', $response->getBody()->read(4));
    }

    /**
     * @depends test_successful_request
     * @psalm-suppress PossiblyNullReference
     */
    public function test_status_report(string $dispatchNumber)
    {
        $request = new StatusReportRequest();
        $request->setChangePeriod(new ChangePeriod(new \DateTime('-1 day'), new \DateTime('+1 day')));
        $request->addOrder(Order::withDispatchNumber($dispatchNumber));

        $response = $this->getClient()->sendStatusReportRequest($request);

        $this->assertFalse($response->hasErrors());

        // База СДЭК не успевает записать данные?
        if ($response->getErrorCode() === 'ERR_ORDERS_NOT_FOUND') {
            $this->markTestSkipped($response->getMessage());
        }

        $this->assertInstanceOf(StatusReportResponse::class, $response);
        $this->assertTrue($response->getDateFirst() < $response->getDateLast());

        $this->assertCount(1, $response->getOrders());

        /** @var Order $order */
        $order = $response->getOrders()[0];

        $this->assertInstanceOf(Order::class, $order);
        $this->assertSame('TESTING123', $order->getActNumber());
        $this->assertSame('Создан', $order->getStatus()->getDescription());

        return Order::withNumberAndDate($order->getNumber(), $order->getStatus()->getDate());
    }

    /**
     * @depends test_status_report
     */
    public function test_print_labels_request(Order $order)
    {
        $request = new PrintLabelsRequest([
            'PrintFormat' => PrintLabelsRequest::PRINT_FORMAT_A5,
        ]);
        $request->addOrder($order);

        $response = $this->getClient()->sendPrintLabelsRequest($request);

        if ($response->hasErrors()) {
            foreach ($response->getMessages() as $message) {
                if ($message->getErrorCode() === 'ERR_API') {
                    $this->markTestSkipped($message->getMessage());
                }

                // Новые заказы попадают в БД СДЭК с задержкой, потому квитанцию не всегда получается сразу получить
                if ($message->getErrorCode() === 'ERR_INVALID_DISPATCHNUMBER') {
                    $this->assertContains($order->getDispatchNumber(), $message->getMessage());
                    $this->markTestSkipped($message->getMessage());
                }

                if ($message->getErrorCode()) {
                    $this->fail($message->getMessage());
                }
            }
        }

        $this->assertFalse($response->hasErrors());
        $this->assertInstanceOf(FileResponse::class, $response);

        $this->assertSame('%PDF', $response->getBody()->read(4));
    }

    /**
     * @depends test_status_report
     */
    public function test_info_report(Order $order)
    {
        $request = new InfoReportRequest();
        $request->addOrder($order);

        $response = $this->getClient()->sendInfoReportRequest($request);

        $this->assertFalse($response->hasErrors());
        $this->assertInstanceOf(InfoReportResponse::class, $response);
        $this->assertCount(1, $response->getOrders());
    }

    /**
     * @depends test_successful_request
     */
    public function test_call_courier(string $dispatchNumber)
    {
        $request = CallCourierRequest::create()->addCall(CallCourier::create([
            'Date'           => new \DateTimeImmutable('next tuesday'),
            'DispatchNumber' => $dispatchNumber,
            'TimeBeg'        => new \DateTimeImmutable('10:00'),
            'TimeEnd'        => new \DateTimeImmutable('17:00'),
            'SendCityCode'   => 44,
            'SenderName'     => 'Проверка Тестович',
            'SendPhone'      => '+78001001010',
        ])->setAddress(Address::create([
            'Street' => 'Тестовая',
            'House'  => '8',
            'Flat'   => '32',
        ])));

        $response = $this->getClient()->sendCallCourierRequest($request);

        $this->assertFalse($response->hasErrors());
        $this->assertInstanceOf(CallCourierResponse::class, $response);
    }
}
