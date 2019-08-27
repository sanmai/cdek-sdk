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
use CdekSDK\Common\CallCourier;
use CdekSDK\Common\Item;
use CdekSDK\Common\Order;
use CdekSDK\Common\Package;
use CdekSDK\Common\Passport;
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
            'Number'          => 'TEST-123456',
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
            'WareKey'            => 'NN0001', // Идентификатор/артикул товара/вложения
            'Cost'               => 500, // Объявленная стоимость товара (за единицу товара)
            'Payment'            => 0, // Оплата за товар при получении (за единицу товара)
            'PaymentVATRate'     => 'VATX', // Ставка НДС включеная в стоимость (payment) товара.
            'PaymentVATSum'      => 5.25, // Сумма НДС, включенного в стоимость (payment) товара.
            'Weight'             => 120, // Вес (за единицу товара, в граммах)
            'Amount'             => 2, // Количество единиц одноименного товара (в штуках)
            'Comment'            => 'Test item',
        ]));

        $order->addPackage($package);

        $request = new DeliveryRequest([
            'Number' => self::TEST_NUMBER,
        ]);
        $request->addOrder($order);

        $this->assertSameAsXML('<?xml version="1.0" encoding="UTF-8"?>
<DeliveryRequest OrderCount="1" Number="TESTING123">
  <Order SendCityCode="44" RecCityPostCode="630001" Number="TEST-123456" RecipientName="Иван Петров" RecipientEmail="petrov@test.ru" Phone="+7 (383) 202-22-50" TariffTypeCode="139">
    <Address Street="Холодильная улица" House="16" Flat="22"/>
    <Package Number="TEST-123456" BarCode="TEST-123456" Weight="500" SizeA="10" SizeB="10" SizeC="10">
      <Item WareKey="NN0001" Cost="500" Payment="0" PaymentVATRate="VATX" PaymentVATSum="5.25" Weight="120" Amount="2" Comment="Test item"/>
    </Package>
  </Order>
</DeliveryRequest>
', $request);
    }

    public function test_can_serialize_regular_request()
    {
        $order = new Order([
            'ClientSide'       => Order::CLIENT_SIDE_SENDER,
            'Number'           => 'TEST-123456',
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
  <Order SendCityCode="44" RecCityPostCode="630001" ClientSide="sender" Number="TEST-123456" RecipientCompany="Петров и партнёры, ООО" RecipientName="Иван Петров" RecipientEmail="petrov@test.ru" Phone="+7 (383) 202-22-50" TariffTypeCode="1" Comment="Это тестовый заказ">
    <Address Street="Холодильная улица" House="16" Flat="22" PvzCode="TST123"/>
    <AddService ServiceCode="17"/>
    <Package Number="TEST-123456" BarCode="TEST-123456" Weight="500" SizeA="10" SizeB="10" SizeC="10"/>
    <Sender Name="Петр Иванов" Company="ЗАО «Рога и Копыта»">
      <Address Street="Морозильная улица" House="2" Flat="101"/>
      <Phone><![CDATA[+7 (283) 101-11-20]]></Phone>
    </Sender>
  </Order>
</DeliveryRequest>
', $request);
    }

    public function test_can_serialize_foreign_delivery_request()
    {
        $order = new Order([
            'DateInvoice'  => new \DateTimeImmutable('2010-10-10'),
            'RecipientINN' => '6449013711',
            'Passport'     => Passport::create([
                'Series'    => '5004',
                'Number'    => '123456',
                'IssueDate' => new \DateTimeImmutable('1999-07-27'),
                'IssuedBy'  => 'УФМС 770001',
                'DateBirth' => new \DateTimeImmutable('1979-07-13'),
            ]),
            'PassportNumber' => '5004 123457',
            'Comment'        => 'Test order',
        ]);

        $package = Package::create([
            'Number'  => 'TEST-123456',
        ]);

        $package->addItem(new Item([
            'WareKey'                  => '123123123',
            'Marking'                  => 'Testing 123',
            'CostEx'                   => 11,
            'Cost'                     => 330,
            'Weight'                   => 1500,
            'WeightBrutto'             => 1650,
            'Amount'                   => 2,
            'CommentEx'                => 'Winter Shoes, Natural Leather. Size 37.',
            'Comment'                  => 'Зимние ботинки, 37 раз. Кожаные',
            'Link'                     => 'https://www.example.com/winter-shoes',
        ]));

        $package->addItem(new Item([
            'WareKey'                  => '345345345',
            'CostEx'                   => 12,
            'Cost'                     => 430,
            'Weight'                   => 1501,
            'WeightBrutto'             => 1651,
            'Amount'                   => 1,
            'CommentEx'                => 'Winter Shoes, Natural Leather. Size 36.',
            'Comment'                  => 'Зимние ботинки, 36 раз. Кожаные',
        ]));

        $order->addPackage($package);

        $request = new DeliveryRequest([
            'Number'          => '12345',
            'ForeignDelivery' => true,
            'Currency'        => 'USD',
        ]);
        $request->addOrder($order);

        $this->assertSameAsXML('<?xml version="1.0" encoding="UTF-8"?>
<DeliveryRequest OrderCount="1" Number="12345" ForeignDelivery="true" Currency="USD">
  <Order DateInvoice="2010-10-10" Comment="Test order" PassportNumber="5004 123457" RecipientINN="6449013711">
    <Package Number="TEST-123456">
      <Item WareKey="123123123" Marking="Testing 123" CostEx="11" Cost="330" Weight="1500" WeightBrutto="1650" Amount="2" Comment="Зимние ботинки, 37 раз. Кожаные" CommentEx="Winter Shoes, Natural Leather. Size 37." Link="https://www.example.com/winter-shoes"/>
      <Item WareKey="345345345" CostEx="12" Cost="430" Weight="1501" WeightBrutto="1651" Amount="1" Comment="Зимние ботинки, 36 раз. Кожаные" CommentEx="Winter Shoes, Natural Leather. Size 36."/>
    </Package>
    <Passport Series="5004" Number="123456" IssueDate="1999-07-27" IssuedBy="УФМС 770001" DateBirth="1979-07-13"/>
  </Order>
</DeliveryRequest>
', $request);
    }

    public function test_with_call_courier()
    {
        $request = DeliveryRequest::create([
            'Number'      => 'foo',
        ])->setCallCourier(CallCourier::create([
            'Comment'        => 'foo',
            'Date'           => new \DateTime('2017-05-06T11:20:45.680+07:00'),
            'LunchBeg'       => new \DateTime('14:00'),
            'LunchEnd'       => new \DateTime('14:30'),
            'SendCityCode'   => 44,
            'SenderName'     => 'Testing',
            'SendPhone'      => '+79138739944',
            'TimeBeg'        => new \DateTime('10:00'),
            'TimeEnd'        => new \DateTime('17:00'),
            'Weight'         => '20',
        ]), Address::create([
            'Street' => 'Тестовая',
            'House'  => '8',
            'Flat'   => '32',
        ]));

        $this->assertSameAsXML('<?xml version="1.0" encoding="UTF-8"?>
<DeliveryRequest OrderCount="0" Number="foo">
  <CallCourier CallCount="1">
    <Call Date="2017-05-06" TimeBeg="10:00" TimeEnd="17:00" LunchBeg="14:00" LunchEnd="14:30" SendCityCode="44" SendPhone="+79138739944" SenderName="Testing" Weight="20" Comment="foo">
      <SendAddress Street="Тестовая" House="8" Flat="32"/>
    </Call>
  </CallCourier>
</DeliveryRequest>
', $request);
    }

    public function test_with_insurance()
    {
        $order = new Order([
            'Number'     => 'TEST-123456',
        ]);

        $order->addService(AdditionalService::create([
            'ServiceCode' => AdditionalService::SERVICE_INSURANCE,
            'Cost'        => 500,
        ]));

        $request = AddDeliveryRequest::create([
            'Number'      => 'foo',
        ]);

        $request->addOrder($order);

        $this->assertSameAsXML('<?xml version="1.0" encoding="UTF-8"?>
<DeliveryRequest OrderCount="1" Number="foo">
  <Order Number="TEST-123456">
    <AddService ServiceCode="2" Cost="500"/>
  </Order>
</DeliveryRequest>
', $request);
    }

    public function test_with_developer_key()
    {
        $request = DeliveryRequest::create([
            'DeveloperKey' => 'abcdefd4621d373cade4e832627b123',
            'Number'       => '888',
        ]);

        $this->assertSameAsXML('<?xml version="1.0" encoding="UTF-8"?>
<DeliveryRequest OrderCount="0" DeveloperKey="abcdefd4621d373cade4e832627b123" Number="888"/>
', $request);
    }
}
