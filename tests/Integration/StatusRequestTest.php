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

namespace Tests\CdekSDK\Integration;

use CdekSDK\Common\Order;
use CdekSDK\Requests\InfoReportRequest;
use CdekSDK\Requests\StatusReportRequest;
use CdekSDK\Responses\InfoReportResponse;
use CdekSDK\Responses\StatusReportResponse;

/**
 * Тест для проверки ответов на запрос данных по произвольным заказам.
 *
 * Для запроса данных задать переменные окружения вида:
 *
 * export CDEK_DISPATCH_NUMBER_1=1022222221
 * export CDEK_DISPATCH_NUMBER_2=1033333332
 *
 * @coversNothing
 * @group integration
 */
class StatusRequestTest extends TestCase
{
    /**
     * @psalm-suppress MixedArgument
     * @psalm-suppress MixedAssignment
     */
    public static function knownDispatchNumbers(): \Generator
    {
        foreach (array_filter(array_keys($_SERVER), function (string $key) {
            return strpos($key, 'CDEK_DISPATCH_NUMBER') === 0;
        }) as $envVar) {
            yield $envVar => [getenv($envVar)];
        }
    }

    /**
     * @dataProvider knownDispatchNumbers
     * @psalm-suppress PossiblyNullReference
     */
    public function test_status_report(string $dispatchNumber)
    {
        $request = new StatusReportRequest();
        $request->addOrder(Order::withDispatchNumber($dispatchNumber));

        $response = $this->getClient()->sendStatusReportRequest($request);

        foreach ($response->getMessages() as $message) {
            if ($message->getErrorCode() === 'ERR_INVALID_DISPATCHNUMBER') {
                $this->markTestSkipped("{$message->getErrorCode()}: {$message->getMessage()}");
            }
        }

        if ($response->getErrorCode() === 'ERR_ORDERS_NOT_FOUND') {
            $this->markTestSkipped($response->getMessage());
        }

        $this->assertFalse($response->hasErrors());

        $this->assertInstanceOf(StatusReportResponse::class, $response);
        $this->assertTrue($response->getDateFirst() < $response->getDateLast());

        $this->assertCount(1, $response->getOrders());

        /** @var Order $order */
        $order = $response->getOrders()[0];

        $this->assertInstanceOf(Order::class, $order);
    }

    /**
     * @dataProvider knownDispatchNumbers
     */
    public function test_info_report(string $dispatchNumber)
    {
        $request = new InfoReportRequest();
        $request->addOrder(Order::withDispatchNumber($dispatchNumber));

        $response = $this->getClient()->sendInfoReportRequest($request);

        foreach ($response->getMessages() as $message) {
            if ($message->getErrorCode() === 'ERR_INVALID_DISPATCHNUMBER') {
                $this->markTestSkipped("{$message->getErrorCode()}: {$message->getMessage()}");
            }
        }

        $this->assertFalse($response->hasErrors());
        $this->assertInstanceOf(InfoReportResponse::class, $response);
        $this->assertCount(1, $response->getOrders());
    }
}
