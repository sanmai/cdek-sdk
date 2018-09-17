<?php
/*
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

namespace Tests\CdekSDK\Integration;

use CdekSDK\Common\Order;
use CdekSDK\Requests\DeliveryRequest;
use CdekSDK\Requests\PrintReceiptsRequest;
use CdekSDK\Responses\PrintErrorResponse;

/**
 * @covers \CdekSDK\Requests\DeliveryRequest
 * @covers \CdekSDK\Responses\DeliveryResponse
 * @covers \CdekSDK\Responses\Types\DeliveryRequest
 *
 * @covers \CdekSDK\Requests\Templates\PrintRequest
 * @covers \CdekSDK\Requests\PrintReceiptsRequest
 * @covers \CdekSDK\Responses\PrintErrorResponse
 *
 * @group integration
 */
class InvalidRequestsTest extends TestCase
{
    public function test_failing_delivery_request()
    {
        $request = new DeliveryRequest();
        $response = $this->getClient()->sendDeliveryRequest($request);

        foreach ($response->getMessages() as $message) {
            $this->assertTrue($message->isError());
            break;
        }
    }

    public function test_failed_print_receipts_request()
    {
        $request = new PrintReceiptsRequest();
        $request->addOrder(Order::withDispatchNumber('invalid'));

        $response = $this->getClient()->sendPrintReceiptsRequest($request);

        $this->assertInstanceOf(PrintErrorResponse::class, $response);

        foreach ($response->getMessages() as $message) {
            $this->assertTrue($message->isError());
        }
    }
}
