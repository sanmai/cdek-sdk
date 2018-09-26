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

use CdekSDK\Common\Address;
use CdekSDK\Common\CallCourier;
use CdekSDK\Requests\CallCourierRequest;

/**
 * @covers \CdekSDK\Requests\CallCourierRequest
 * @covers \CdekSDK\Common\CallCourier
 */
class CallCourierRequestTest extends TestCase
{
    public function test_can_serialize()
    {
        $request = CallCourierRequest::create()->addCall(CallCourier::create([
            'Comment' => 'foo',
            'Date' => new \DateTime('2017-03-14T14:27:46.628+07:00'),
            'DispatchNumber' => '1039547805',
            'LunchBeg' => new \DateTime('14:00'),
            'LunchEnd' => new \DateTime('14:30'),
            'SendCityCode' => 44,
            'SenderName' => 'Testing',
            'SendPhone' => '+79138739944',
            'TimeBeg' => new \DateTime('10:00'),
            'TimeEnd' => new \DateTime('17:00'),
            'Weight' => '20',
            'IgnoreTime' => true,
        ])->setAddress(Address::create([
            'Street' => 'Тестовая',
            'House' => '8',
            'Flat' => '32',
        ])));

        $this->assertSameAsXML('<?xml version="1.0" encoding="UTF-8"?>
<CallCourier CallCount="1">
  <Call Date="2017-03-14" TimeBeg="10:00" TimeEnd="17:00" LunchBeg="14:00" LunchEnd="14:30" SendCityCode="44" SendPhone="+79138739944" SenderName="Testing" Weight="20" Comment="foo" DispatchNumber="1039547805" IgnoreTime="true">
    <Address Street="Тестовая" House="8" Flat="32"/>
  </Call>
</CallCourier>
', $request);
    }
}
