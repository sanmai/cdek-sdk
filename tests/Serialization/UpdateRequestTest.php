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

use CdekSDK\Common\Item;
use CdekSDK\Common\Order;
use CdekSDK\Common\Package;
use CdekSDK\Requests\UpdateRequest;

/**
 * @covers \CdekSDK\Requests\UpdateRequest
 */
class UpdateRequestTest extends TestCase
{
    const TEST_NUMBER = 'TESTING123';

    public function test_can_serialize()
    {
        $request = UpdateRequest::create([
            'Number' => 'foo',
        ])->addOrder(Order::create([
            'Number' => 'bar',
        ])->addPackage(Package::create([
            'Number' => 'baz',
        ])->addItem(new Item(['WareKey' => 'foo']))));

        $this->assertSameAsXML('<?xml version="1.0" encoding="UTF-8"?>
<UpdateRequest OrderCount="1" Number="foo">
  <Order Number="bar">
    <Package Number="baz">
      <Item WareKey="foo"/>
    </Package>
  </Order>
</UpdateRequest>
', $request);
    }

    public function test_can_serialize_example()
    {
        $request = UpdateRequest::create([
            'Number' => 'foo',
        ])->addOrder(Order::create([
            'Number'                   => 'bar',
            'DeliveryRecipientCost'    => 10.02,
            'DeliveryRecipientVATRate' => 'VATX',
            'DeliveryRecipientVATSum'  => 0.0,
        ])->addPackage(Package::create([
            'Number'  => 'baz',
            'BarCode' => '12345',
            'SizeA'   => 2.0,
            'SizeB'   => 3.0,
            'SizeC'   => 4.0,
            'Weight'  => 100.0,
        ])->addItem(new Item([
            'Amount'         => 2,
            'Comment'        => 'comment',
            'Cost'           => 8.0,
            'Payment'        => 10.0,
            'PaymentVATRate' => 'VATX',
            'PaymentVATSum'  => 0.0,
            'WareKey'        => 'testing',
            'Weight'         => 1.0,
        ]))));

        $this->assertSameAsXML('<?xml version="1.0" encoding="UTF-8"?>
<UpdateRequest OrderCount="1" Number="foo">
  <Order Number="bar" DeliveryRecipientCost="10.02" DeliveryRecipientVATRate="VATX" DeliveryRecipientVATSum="0">
    <Package Number="baz" BarCode="12345" Weight="100" SizeA="2" SizeB="3" SizeC="4">
      <Item WareKey="testing" Cost="8" Payment="10" PaymentVATRate="VATX" PaymentVATSum="0" Weight="1" Amount="2" Comment="comment"/>
    </Package>
  </Order>
</UpdateRequest>
', $request);
    }

    public function test_can_serialize_recipient_email()
    {
        $request = UpdateRequest::create([
            'Number' => 'foo',
        ])->addOrder(Order::create([
            'RecipientEmail' => 'test@example.com',
        ]));

        $this->assertSameAsXML('<?xml version="1.0" encoding="UTF-8"?>
<UpdateRequest OrderCount="1" Number="foo">
  <Order RecipientEmail="test@example.com"/>
</UpdateRequest>
', $request);
    }
}
