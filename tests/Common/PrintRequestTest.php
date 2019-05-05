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

use CdekSDK\Common\Fillable;
use CdekSDK\Common\Order;
use CdekSDK\Requests\Concerns\Authorized;
use CdekSDK\Requests\Concerns\RequestCore;
use CdekSDK\Requests\Templates\PrintRequest;
use PHPUnit\Framework\TestCase;

/**
 * @covers \CdekSDK\Requests\Templates\PrintRequest
 * @covers \CdekSDK\Common\Order
 */
class PrintRequestTest extends TestCase
{
    public function test_it_rejects_order_without_dispatch_number()
    {
        $request = new class() extends PrintRequest {
            use Fillable;
            use Authorized;
            use RequestCore;

            const ADDRESS = '';
            const METHOD = '';
        };

        $request = $request->addOrder(Order::create([
            'DispatchNumber' => 'testing',
        ]));

        $this->assertSame(1, $request->getOrderCount());

        $this->expectException(\BadMethodCallException::class);

        $request->addOrder(Order::create([
            'Number' => 'invalid',
        ]));
    }
}
