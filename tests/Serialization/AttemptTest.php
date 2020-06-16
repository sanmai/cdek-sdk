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

use CdekSDK\Common\Attempt;

/**
 * @covers \CdekSDK\Common\Attempt
 */
class AttemptTest extends TestCase
{
    public function test_can_serialize()
    {
        $attempt = Attempt::create([
            'ID'                       => 500,
            'Date'                     => new \DateTimeImmutable('2018-10-01'),
            'Comment'                  => 'Testing',
            'DeliveryRecipientCost'    => 1000,
            'DeliveryRecipientVATRate' => 'VATX',
            'DeliveryRecipientVATSum'  => 100,
            'TimeBeg'                  => new \DateTimeImmutable('10:00'),
            'TimeEnd'                  => new \DateTimeImmutable('12:00'),
        ]);

        $this->assertSameAsXML('<?xml version="1.0" encoding="UTF-8"?>
<Attempt ID="500" Date="2018-10-01" Comment="Testing" DeliveryRecipientCost="1000" DeliveryRecipientVATRate="VATX" DeliveryRecipientVATSum="100" TimeBeg="10:00:00" TimeEnd="12:00:00"/>
', $attempt);
    }

    public function test_can_serialize_with_address()
    {
        $attempt = Attempt::create([
            'ID'                       => 123,
            'Address'                  => \CdekSDK\Common\Address::create([
                'PvzCode' => 'TST123',
            ]),
        ]);

        $this->assertSameAsXML('<?xml version="1.0" encoding="UTF-8"?>
<Attempt ID="123">
  <Address PvzCode="TST123"/>
</Attempt>
', $attempt);
    }
}
