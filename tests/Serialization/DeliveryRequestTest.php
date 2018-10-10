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

namespace Tests\CdekSDK\Serialization;

use CdekSDK\Common\AdditionalService;
use CdekSDK\Common\Address;
use CdekSDK\Common\City;
use CdekSDK\Common\Item;
use CdekSDK\Common\Order;
use CdekSDK\Common\Package;
use CdekSDK\Common\Sender;
use CdekSDK\Requests\AddDeliveryRequest;
use CdekSDK\Requests\DeliveryRequest;

/**
 * @covers \CdekSDK\Requests\DeliveryRequest
 * @covers \CdekSDK\Requests\AddDeliveryRequest
 */
class DeliveryRequestTest extends TestCase
{
    const TEST_NUMBER = 'TESTING123';

    public function test_can_serialize()
    {
        $request = DeliveryRequest::create([
            'Number' => 'foo',
        ])->addOrder(Order::create([
            'Number' => 'bar',
        ])->addPackage(Package::create([
            'Number' => 'baz',
        ])->addItem(new Item(['WareKey' => 'foo']))));

        $this->assertSameAsXML('<?xml version="1.0" encoding="UTF-8"?>
<DeliveryRequest OrderCount="1" Number="foo">
  <Order Number="bar">
    <Package Number="baz">
      <Item WareKey="foo"/>
    </Package>
  </Order>
</DeliveryRequest>
', $request);
    }

    public function test_can_serialize_im_request()
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

        $this->assertSameAsXML('<?xml version="1.0" encoding="UTF-8"?>
<DeliveryRequest OrderCount="1" Number="TESTING123">
  <Order SendCityCode="44" SendCityPostCode="" RecCityPostCode="630001" Number="TEST-123456" RecipientName="Иван Петров" RecipientEmail="petrov@test.ru" Phone="+7 (383) 202-22-50" TariffTypeCode="139">
    <Address Street="Холодильная улица" House="16" Flat="22"/>
    <Package Number="TEST-123456" BarCode="TEST-123456" Weight="500" SizeA="10" SizeB="10" SizeC="10">
      <Item WareKey="NN0001" Cost="500" Payment="0" Weight="120" Amount="2" Comment="Test item"/>
    </Package>
    <SendCity Code="44"/>
    <RecCity PostCode="630001"/>
  </Order>
</DeliveryRequest>
', $request);
    }

    public function test_can_serialize_regular_request()
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
            'Phone'   => '+7 (283) 101-11-20',
        ])->setAddress(Address::create([
            'Street' => 'Морозильная улица',
            'House'  => '2',
            'Flat'   => '101',
        ])));

        $order->setAddress(Address::create([
            'Street'  => 'Холодильная улица',
            'House'   => '16',
            'Flat'    => '22',
            'PvzCode' => 'TST123',
        ]));

        $package = Package::create([
            'Number'  => 'TEST-123456',
            'BarCode' => 'TEST-123456',
            'Weight'  => 500, // Общий вес (в граммах)
            'SizeA'   => 10, // Длина (в сантиметрах), в пределах от 1 до 1500
            'SizeB'   => 10,
            'SizeC'   => 10,
        ]);

        $order->addPackage($package);

        $order->addService(AdditionalService::create(AdditionalService::SERVICE_DELIVERY_TO_DOOR));

        $request = new AddDeliveryRequest([
            'Number'          => self::TEST_NUMBER,
            'ForeignDelivery' => false,
            'Currency'        => 'RUB',
        ]);
        $request->addOrder($order);

        $this->assertSameAsXML('<?xml version="1.0" encoding="UTF-8"?>
<DeliveryRequest OrderCount="1" Number="TESTING123" ForeignDelivery="false" Currency="RUB">
  <Order SendCityCode="44" SendCityPostCode="" RecCityPostCode="630001" ClientSide="sender" Number="TEST-123456" RecipientCompany="Петров и партнёры, ООО" RecipientName="Иван Петров" RecipientEmail="petrov@test.ru" Phone="+7 (383) 202-22-50" TariffTypeCode="1" Comment="Это тестовый заказ">
    <Address Street="Холодильная улица" House="16" Flat="22" PvzCode="TST123"/>
    <AddService ServiceCode="17"/>
    <Package Number="TEST-123456" BarCode="TEST-123456" Weight="500" SizeA="10" SizeB="10" SizeC="10"/>
    <SendCity Code="44"/>
    <RecCity PostCode="630001"/>
    <Sender Name="Петр Иванов" Company="ЗАО «Рога и Копыта»">
      <Address Street="Морозильная улица" House="2" Flat="101"/>
      <Phone><![CDATA[+7 (283) 101-11-20]]></Phone>
    </Sender>
  </Order>
</DeliveryRequest>
', $request);
    }
}
