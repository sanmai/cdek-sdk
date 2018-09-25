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

use CdekSDK\Common\ChangePeriod;
use CdekSDK\Common\Order;
use CdekSDK\Requests\InfoReportRequest;

/**
 * @covers \CdekSDK\Requests\InfoReportRequest
 */
class InfoReportRequestTest extends TestCase
{
    public function test_can_serialize()
    {
        $request = new InfoReportRequest();
        $request = $request->setChangePeriod(new ChangePeriod(new \DateTimeImmutable('2018-01-01T00:00:00+0000'), new \DateTimeImmutable('2018-02-02T00:00:00+0000')));

        $this->assertSameAsXML('<?xml version="1.0" encoding="UTF-8"?>
<InfoRequest>
  <ChangePeriod DateFirst="2018-01-01T00:00:00+0000" DateLast="2018-02-02T00:00:00+0000" DateBeg="2018-01-01T00:00:00+0000" DateEnd="2018-02-02T00:00:00+0000"/>
</InfoRequest>
', $request);
    }

    public function test_it_works_with_orders()
    {
        $request = new InfoReportRequest();
        $request->addOrder(Order::withDispatchNumber('123'));
        $request = $request->addOrder(Order::withDispatchNumber('456'));

        $this->assertSameAsXML('<?xml version="1.0" encoding="UTF-8"?>
<InfoRequest>
  <Order DispatchNumber="123"/>
  <Order DispatchNumber="456"/>
</InfoRequest>
', $request);
    }
}
