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

namespace Tests\CdekSDK\Common;

use CdekSDK\Common\Order;

/**
 * @covers \CdekSDK\Common\Order
 */
class OrderTest extends TestCase
{
    public function test_it_gets_id_from_dispatch_number()
    {
        $order = Order::withDispatchNumber('testing123');
        $this->assertSame('testing123', $order->getId());
        $this->assertSame('testing123', (string) $order);
    }

    public function test_it_gets_id_from_date_number()
    {
        $order = Order::withNumberAndDate('foo', new \DateTime('2001-01-01'));
        $this->assertSame('2001-01-01/foo', $order->getId());
        $this->assertSame('2001-01-01/foo', (string) $order);
    }

    public function test_it_fails_with_no_id()
    {
        $this->expectException(\BadMethodCallException::class);

        $order = new Order();
        $order->getId();
    }

    public function test_constants_exists()
    {
        foreach ([
            Order::CLIENT_SIDE_SENDER,
            Order::CLIENT_SIDE_RECEIVED,
            Order::CLIENT_SIDE_OTHER,
        ] as $value) {
            $this->assertNotNull($value);
        }
    }

    public function test_it_fails_with_type_error()
    {
        $this->expectException(\TypeError::class);

        $order = new Order();
        $order->getReturnOrder();
    }
}
