<?php
/*
 * This file is part of Cdek SDK package.
 *
 * © Appwilio (http://appwilio.com), greabock (https://github.com/greabock), JhaoDa (https://github.com/jhaoda)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Tests\Appwilio\CdekSDK\Integration;

use Appwilio\CdekSDK\Common\Address;
use Appwilio\CdekSDK\Common\City;
use Appwilio\CdekSDK\Common\Item;
use Appwilio\CdekSDK\Common\Order;
use Appwilio\CdekSDK\Common\Package;
use Appwilio\CdekSDK\Requests\DeleteRequest;
use Appwilio\CdekSDK\Requests\DeliveryRequest;

/**
 * @covers \Appwilio\CdekSDK\Requests\DeliveryRequest
 * @covers \Appwilio\CdekSDK\Responses\DeliveryResponse
 * @covers \Appwilio\CdekSDK\Common\DeliveryRequest
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

    public function test_successful_request()
    {
        $this->getClient()->sendDeleteRequest(DeleteRequest::create([
            'Number' => self::TEST_NUMBER,
        ])->addOrder(new Order([
            'Number' => 'TEST-123456',
        ])));

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
            'Number' => 'TESTING123',
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
    }
}
