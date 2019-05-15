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
use CdekSDK\Common\Attempt;
use CdekSDK\Common\CallCourier;
use CdekSDK\Common\DeliveryRecipientCostAdv;
use CdekSDK\Common\Order;
use CdekSDK\Common\Package;
use CdekSDK\Common\Seller;
use CdekSDK\Common\Sender;

/**
 * @covers \CdekSDK\Common\Order
 * @covers \CdekSDK\Common\Sender
 */
class OrderTest extends TestCase
{
    public function test_can_serialize_address()
    {
        $order = new Order();
        $order = $order->setSender(Sender::create([
            'Name'    => 'Отправителев',
            'Company' => 'Testing',
            'Phone'   => '+9-999-999-9999',
        ])->setAddress(Address::create([
            'Street' => 'Тестовая',
            'House'  => '8',
            'Flat'   => '32',
        ])));

        $this->assertSameAsXML('<?xml version="1.0" encoding="UTF-8"?>
<Order>
  <Sender Name="Отправителев" Company="Testing">
    <Address Street="Тестовая" House="8" Flat="32"/>
    <Phone><![CDATA[+9-999-999-9999]]></Phone>
  </Sender>
</Order>
', $order);
    }

    public function test_can_serialize_own_address()
    {
        $order = new Order();
        $order = $order->setAddress(Address::create([
            'Street' => 'Тестовая',
            'House'  => '8',
            'Flat'   => '32',
        ]));

        $this->assertSameAsXML('<?xml version="1.0" encoding="UTF-8"?>
<Order>
  <Address Street="Тестовая" House="8" Flat="32"/>
</Order>
', $order);
    }

    public function test_can_serialize_call_courier()
    {
        $order = Order::withDispatchNumber('123456');
        $order = $order->callCourier(CallCourier::create([
            'SendCityCode'   => 44,
            'SenderName'     => 'Проверка Тестович',
            'SendPhone'      => '+78001001010',
        ]));

        $this->assertSameAsXML('<?xml version="1.0" encoding="UTF-8"?>
<Order DispatchNumber="123456">
  <CallCourier>
    <Call SendCityCode="44" SendPhone="+78001001010" SenderName="Проверка Тестович"/>
  </CallCourier>
</Order>
', $order);
    }

    public function test_can_serialize_with_service()
    {
        $order = Order::withDispatchNumber('123456');
        $order = $order->addService(AdditionalService::create([
            'ServiceCode'   => 1,
        ]));

        $this->assertSameAsXML('<?xml version="1.0" encoding="UTF-8"?>
<Order DispatchNumber="123456">
  <AddService ServiceCode="1"/>
</Order>
', $order);
    }

    public function test_can_serialize_with_schedule_attempt()
    {
        $order = Order::withDispatchNumber('123456');
        $order = $order->addScheduleAttempt(Attempt::create([
            'Phone'     => '+78001001010',
        ]));

        $this->assertSameAsXML('<?xml version="1.0" encoding="UTF-8"?>
<Order DispatchNumber="123456">
  <Schedule>
    <Attempt Phone="+78001001010"/>
  </Schedule>
</Order>
', $order);
    }

    public function test_can_serialize_with_package()
    {
        $order = Order::withDispatchNumber('123456');
        $order = $order->addPackage(Package::create([
            'Number' => '123',
        ]));

        $this->assertSameAsXML('<?xml version="1.0" encoding="UTF-8"?>
<Order DispatchNumber="123456">
  <Package Number="123"/>
</Order>
', $order);
    }

    public function test_can_serialize_with_delivery_surcharge()
    {
        $order = Order::withDispatchNumber('1234567');

        $order = $order->addDeliveryRecipientCostAdv(DeliveryRecipientCostAdv::create([
            'Threshold' => 2000,
            'Sum'       => 150,
            'VATRate'   => 'vat10',
            'VATSum'    => 45.906,
        ]));

        $order = $order->addService(AdditionalService::create([
            'ServiceCode'   => 30,
        ]));

        $this->assertSameAsXML('<?xml version="1.0" encoding="UTF-8"?>
<Order DispatchNumber="1234567">
  <DeliveryRecipientCostAdv Threshold="2000" Sum="150" VATRate="vat10" VATSum="45.906"/>
  <AddService ServiceCode="30"/>
</Order>
', $order);
    }

    public function test_order_with_number_only()
    {
        $order = Order::withNumberAndDate('ORD-123');

        $this->assertSame('ORD-123', $order->getNumber());

        $this->assertSameAsXML('<?xml version="1.0" encoding="UTF-8"?>
<Order Number="ORD-123"/>
', $order);
    }

    public function test_can_serialize_true_seller()
    {
        $order = new Order();
        $order = $order->setSeller(Seller::create([
            'Address'         => 'с. Красный Яр, ул. Садовая, д. 20',
            'Name'            => 'ООО "Пример"',
            'INN'             => '6449013711',
            'Phone'           => '+74950001122',
            'OwnershipForm'   => 541,
        ]));

        $this->assertSameAsXML('<?xml version="1.0" encoding="UTF-8"?>
<Order>
  <Seller Address="с. Красный Яр, ул. Садовая, д. 20" Name="ООО &quot;Пример&quot;" INN="6449013711" Phone="+74950001122" OwnershipForm="541"/>
</Order>
', $order);
    }
}
