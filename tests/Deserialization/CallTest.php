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

namespace Tests\CdekSDK\Deserialization;

use CdekSDK\Common\Call;

/**
 * @covers \CdekSDK\Common\Call
 * @covers \CdekSDK\Common\CallGood
 * @covers \CdekSDK\Common\CallFail
 * @covers \CdekSDK\Common\CallDelay
 */
class CallTest extends TestCase
{
    public function test_successful_request()
    {
        $call = $this->getSerializer()->deserialize('<?xml version="1.0" encoding="UTF-8"?>
<Call>
<CallGood>
<Good Date="2018-04-01T14:15:46+00:00" DateDeliv="2018-04-02" />
<Good Date="2018-04-03T16:56:41+00:00" DateDeliv="2018-04-07" />
</CallGood>
<CallFail>
<Fail Date="2018-04-02T15:35:47+00:00" ReasonCode="3" ReasonDescription="Абонент недоступен" />
</CallFail>
<CallDelay>
<Delay Date="2018-04-03T16:56:41+00:00" DateNext="2018-04-06T16:56:41+00:00" />
</CallDelay>
<CallDelay>
<Delay Date="2019-03-11T11:50:23+00:00" DateNext="2019-03-12" />
</CallDelay>
</Call>', Call::class, 'xml');

        /** @var $call Call */
        $this->assertCount(2, $call->getCallGood());
        $this->assertCount(1, $call->getCallFail());
        $this->assertCount(1, $call->getCallDelay());

        $this->assertSame('2018-04-01', $call->getCallGood()[0]->getDate()->format('Y-m-d'));
        $this->assertSame('2018-04-02', $call->getCallGood()[0]->getDateDeliv()->format('Y-m-d'));

        $this->assertSame('2018-04-02', $call->getCallFail()[0]->getDate()->format('Y-m-d'));
        $this->assertSame(3, $call->getCallFail()[0]->getReasonCode());
        $this->assertSame('Абонент недоступен', $call->getCallFail()[0]->getReasonDescription());

        $this->assertSame('2018-04-03', $call->getCallDelay()[0]->getDate()->format('Y-m-d'));
        $this->assertSame('2018-04-06', $call->getCallDelay()[0]->getDateNext()->format('Y-m-d'));

        $this->assertSame('2019-03-11', $call->getCallDelay()[1]->getDate()->format('Y-m-d'));
        $this->assertSame('2019-03-12', $call->getCallDelay()[1]->getDateNext()->format('Y-m-d'));
    }
}
