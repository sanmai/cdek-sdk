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
use CdekSDK\Requests\UpdateRequest;
use CdekSDK\Responses\CallCourierResponse;
use CdekSDK\Responses\DeleteResponse;
use CdekSDK\Responses\FileResponse;
use CdekSDK\Responses\InfoReportResponse;
use CdekSDK\Responses\StatusReportResponse;
use CdekSDK\Responses\UpdateResponse;

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
    const TEST_NUMBER = 'TESTING%s';

    public function test_delete_success()
    {
        $response = $this->getClient()->sendDeleteRequest(DeleteRequest::create([
            'Number' => self::formatTestNumber(self::TEST_NUMBER),
        ])->addOrder(new Order([
            'Number' => self::formatTestNumber('TEST-%s'),
        ])));

        $this->assertInstanceOf(DeleteResponse::class, $response);

        foreach ($response->getMessages() as $message) {
            $this->assertNotEmpty($message->getMessage());
        }

        /** @var Order $order */
        foreach ($response->getOrders() as $order) {
            $this->assertSame(self::formatTestNumber('TEST-%s'), $order->getNumber());
        }
    }

    /**
     * @depends test_delete_success
     */
    public function test_successful_request_for_shop(): string
    {
        $order = new Order([
            'Number'          => self::formatTestNumber('TEST-%s'),
            'SendCityCode'    => 44, // Москва
            'RecCityPostCode' => '630001', // Новосибирск
            'RecipientName'   => 'Иван Петров',
            'RecipientEmail'  => 'petrov@test.ru',
            'Phone'           => '+7 (383) 202-22-50',
            'TariffTypeCode'  => 139, // Посылка дверь-дверь
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
            'Number' => self::formatTestNumber(self::TEST_NUMBER),
        ]);
        $request->addOrder($order);

        $response = $this->getClient()->sendDeliveryRequest($request);

        $this->skipIfKnownAPIErrorCode($response);

        foreach ($response->getMessages() as $message) {
            if ($message->getErrorCode() === 'ERR_AUTH') {
                $this->fail(\preg_replace('/[a-f0-9]{16,}/', '[secure]', "Access keys require an update; received {$message->getErrorCode()}: {$message->getMessage()}"));
            }
        }

        // В разных версиях API этот запрос может сработать и нет. Если всё-таки да, используем это для тестирования.
        foreach ($response->getOrders() as $order) {
            $this->assertNotEmpty($order->getDispatchNumber());

            return $order->getDispatchNumber();
        }

        $this->assertTrue($response->hasErrors(), 'No errors found');
        $this->assertCount(2, $response->getMessages());

        foreach ($response->getMessages() as $message) {
            if ($message->getErrorCode() === 'ERR_ORDER_DUBL_EXISTS' && env('CI')) {
                $this->markTestIncomplete($message->getMessage());
            }

            $this->assertNotEmpty($message->getErrorCode(), $message->getMessage());
            $this->assertSame('ERR_INVALID_TARIFF_WITH_CLIENT', $message->getErrorCode());

            // "Для выбранного тарифа клиент-плательщик должен быть задан и иметь ИМ-договор"
            // Это означает что с нашими реквизитами нельзя создать заказ для ИМ
            return '';
        }

        $this->fail();

        return '';
    }

    /**
     * @depends test_successful_request_for_shop
     */
    public function test_successful_request_for_delivery(string $dispatchNumber): string
    {
        // Попробуем создать заказ на доставку

        $order = new Order([
            'ClientSide'       => Order::CLIENT_SIDE_SENDER,
            'Number'           => self::formatTestNumber('TEST-%s'),
            'SendCityCode'     => 44, // Москва
            'RecCityPostCode'  => '630001', // Новосибирск
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
            'Number'          => self::formatTestNumber(self::TEST_NUMBER),
            'ForeignDelivery' => false,
            'Currency'        => 'RUB',
        ]);
        $request->addOrder($order);

        $response = $this->getClient()->sendAddDeliveryRequest($request);

        $this->skipIfKnownAPIErrorCode($response);

        foreach ($response->getMessages() as $message) {
            if ($message->getErrorCode() === 'ERROR_VALIDATE_SENDER_CONTRAGENT_DELETED') {
                $this->markTestSkipped($message->getMessage());
            }
        }

        foreach ($response->getMessages() as $message) {
            $this->assertEmpty($message->getErrorCode(), $message->getMessage());
        }

        $this->assertFalse($response->hasErrors());

        foreach ($response->getOrders() as $order) {
            $this->assertNotEmpty($order->getDispatchNumber());
            // Номер заказа не передаётся для заказов доставки
            //$this->assertSame(self::formatTestNumber('TEST-%s'), $order->getNumber());
        }

        return $order->getDispatchNumber();
    }

    /**
     * @depends test_successful_request_for_shop
     * @depends test_successful_request_for_delivery
     */
    public function test_successful_request_any(string $dispatchNumberShop, string $dispatchNumberDelivery)
    {
        $dispatchNumber = $dispatchNumberShop !== '' ? $dispatchNumberShop : $dispatchNumberDelivery;

        $this->assertNotEmpty($dispatchNumber);

        return $dispatchNumber;
    }

    /**
     * @depends test_successful_request_any
     */
    public function test_update_request(string $dispatchNumber)
    {
        if ($dispatchNumber === '') {
            $this->markTestSkipped('Используются реквизиты для доставки');
        }

        $request = UpdateRequest::create([
            'Number' => self::formatTestNumber(self::TEST_NUMBER),
        ])->addOrder(Order::create([
            'DispatchNumber' => $dispatchNumber,
            'Number'         => self::formatTestNumber('TEST-%s'),
        ])->setAddress(Address::create([
            'Street' => 'Морозильная улица',
            'House'  => '2',
            'Flat'   => '101',
        ]))->addPackage(Package::create([
            'Number'  => 'TEST-123456',
            'BarCode' => 'TEST-123456',
            'Weight'  => 600, // Общий вес (в граммах)
            'SizeA'   => 10, // Длина (в сантиметрах), в пределах от 1 до 1500
            'SizeB'   => 10,
            'SizeC'   => 10,
        ])->addItem(new Item([
            'WareKey' => 'NN0001', // Идентификатор/артикул товара/вложения
            'Cost'    => 500, // Объявленная стоимость товара (за единицу товара)
            'Payment' => 0, // Оплата за товар при получении (за единицу товара)
            'Weight'  => 120, // Вес (за единицу товара, в граммах)
            'Amount'  => 2, // Количество единиц одноименного товара (в штуках)
            'Comment' => 'Test item',
        ]))));

        $response = $this->getClient()->sendUpdateRequest($request);

        $this->assertInstanceOf(UpdateResponse::class, $response);

        $this->skipIfKnownAPIErrorCode($response, [
            'ERR_ORDER_NOTFIND', // здесь и ниже - случай когда БД СДЭК отстаёт
            'ERR_ORDER_NUMBER_NOT_EXIST',
        ]);

        foreach ($response->getMessages() as $message) {
            $this->assertEmpty($message->getErrorCode(), $message->getMessage());
        }

        $this->assertFalse($response->hasErrors());

        foreach ($response->getOrders() as $order) {
            $this->assertSame(self::formatTestNumber('TEST-%s'), $order->getNumber());
            $this->assertNotEmpty($order->getDispatchNumber());
        }
    }

    /**
     * @depends test_successful_request_any
     */
    public function test_print_receipts_request(string $dispatchNumber)
    {
        $request = new PrintReceiptsRequest();
        $request->addOrder(Order::withDispatchNumber($dispatchNumber));

        $response = $this->getClient()->sendPrintReceiptsRequest($request);

        $this->skipIfKnownAPIErrorCode($response, [
            'ERR_API',
        ]);

        if ($response->hasErrors()) {
            foreach ($response->getMessages() as $message) {
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
     * @depends test_successful_request_any
     * @psalm-suppress PossiblyNullReference
     */
    public function test_status_report(string $dispatchNumber)
    {
        $request = new StatusReportRequest();
        $request->setChangePeriod(new ChangePeriod(new \DateTime('-1 day'), new \DateTime('+1 day')));
        $request->addOrder(Order::withDispatchNumber($dispatchNumber));

        $response = $this->getClient()->sendStatusReportRequest($request);

        $this->skipIfKnownAPIErrorCode($response, [
            'ERR_ORDERS_NOT_FOUND', // здесь и ниже - случай когда БД СДЭК отстаёт
            'ERR_ORDER_NUMBER_NOT_EXIST',
        ]);

        $this->assertFalse($response->hasErrors());

        $this->assertInstanceOf(StatusReportResponse::class, $response);
        $this->assertTrue($response->getDateFirst() < $response->getDateLast());

        $this->assertCount(1, $response->getOrders());

        /** @var Order $order */
        $order = $response->getOrders()[0];

        $this->assertInstanceOf(Order::class, $order);
        $this->assertSame($dispatchNumber, $order->getDispatchNumber());
        $this->assertSame('Создан', $order->getStatus()->getDescription());

        if ($order->getActNumber() !== '' && !$this->isTestEndpointUsed()) {
            $this->assertSame(self::formatTestNumber(self::TEST_NUMBER), $order->getActNumber());
        }

        if ($order->getNumber() === '' && $this->isTestEndpointUsed()) {
            // Тестовое API иногда возвращает такое - тестирование продолжаем как можно
            return Order::withDispatchNumber($order->getDispatchNumber());
        }

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
     * @depends test_successful_request_any
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
