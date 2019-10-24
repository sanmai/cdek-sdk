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

use CdekSDK\Contracts\HasErrorCode;
use CdekSDK\Responses\CalculationWithTariffListResponse;
use CdekSDK\Responses\Types\TariffResult;
use Tests\CdekSDK\Fixtures\FixtureLoader;

/**
 * @covers \CdekSDK\Responses\CalculationWithTariffListResponse
 * @covers \CdekSDK\Responses\Types\Result
 * @covers \CdekSDK\Responses\Types\Error
 * @covers \CdekSDK\Responses\Types\TariffResult
 * @covers \CdekSDK\Responses\Concerns\WrapsResult
 */
class CalculationWithTariffListResponseTest extends TestCase
{
    public function test_it_deserializes_errors()
    {
        $response = $this->getSerializer()->deserialize(FixtureLoader::load('CalculationWithTariffListResponseError.json'), CalculationWithTariffListResponse::class, 'json');

        /** @var $response CalculationWithTariffListResponse */
        $this->assertInstanceOf(CalculationWithTariffListResponse::class, $response);

        $this->assertTrue($response->hasErrors());

        $this->assertCount(4, $response->getErrors());

        foreach ($response->getErrors() as $error) {
            $this->assertSame('Не задан город-отправитель', $error->getText());
            $this->assertSame(7, $error->getCode());

            $this->assertSame('Не задан город-отправитель', $error->getMessage());
            $this->assertSame('7', $error->getErrorCode());
            break;
        }

        $this->assertCount(4, $response->getMessages());

        foreach ($response->getMessages() as $error) {
            $this->assertSame('Не задан город-отправитель', $error->getMessage());
            $this->assertSame('7', $error->getErrorCode());
            break;
        }
    }

    public function test_it_deserializes_correct_response()
    {
        $response = $this->getSerializer()->deserialize(FixtureLoader::load('CalculationRequestTariffListResponse.json'), CalculationWithTariffListResponse::class, 'json');

        /** @var $response CalculationWithTariffListResponse */
        $this->assertInstanceOf(CalculationWithTariffListResponse::class, $response);

        $this->assertFalse($response->hasErrors());

        $this->assertCount(0, $response->getErrors());

        $this->assertCount(2, $response);
        $this->assertCount(2, $response->getResults());

        foreach ($response->getResults() as $result) {
            $this->assertInstanceOf(TariffResult::class, $result);
        }

        $result = $response->getResults()[0];

        $this->assertTrue($result->getStatus());
        $this->assertSame(1, $result->getTariffId());
        $this->assertSame(1, $result->getResult()->getTariffId());
        $this->assertSame(1190.0, $result->getPrice());
        $this->assertSame(2, $result->getDeliveryPeriodMin());
        $this->assertSame(3, $result->getDeliveryPeriodMax());
        $this->assertSame(1190.0, $result->getPriceByCurrency());
        $this->assertSame('RUB', $result->getCurrency());

        $this->assertCount(0, $result->getResult()->getMessages());
        $this->assertSame([], $result->getResult()->jsonSerialize());

        $this->assertCount(1, $result->getAdditionalServices());
        $this->assertSame(2, $result->getAdditionalServices()[0]->getId());
        $this->assertSame('Страхование', $result->getAdditionalServices()[0]->getTitle());
        $this->assertSame(300.0, $result->getAdditionalServices()[0]->getPrice());
        $this->assertSame(0.8, $result->getAdditionalServices()[0]->getRate());

        $result = $response->getResults()[1];

        $this->assertFalse($result->getStatus());
        $this->assertSame(8, $result->getTariffId());
        $this->assertCount(1, $result->getErrors());
        $this->assertCount(1, $result->getMessages());
        $this->assertSame([], $result->jsonSerialize());

        $error = $result->getErrors()[0];

        $this->assertSame('Невозможно осуществить доставку по этому направлению при заданных условиях', $error->getText());
        $this->assertSame(3, $error->getCode());

        $this->assertSame('Невозможно осуществить доставку по этому направлению при заданных условиях', $error->getMessage());
        $this->assertSame('3', $error->getErrorCode());

        $this->expectException(\RuntimeException::class);
        $result->getPrice();
    }

    public function test_it_deserializes_example_response()
    {
        $response = $this->getSerializer()->deserialize(FixtureLoader::load('CalculationRequestTariffListResponseExample.json'), CalculationWithTariffListResponse::class, 'json');

        /** @var $response CalculationWithTariffListResponse */
        $this->assertInstanceOf(CalculationWithTariffListResponse::class, $response);

        $this->assertFalse($response->hasErrors());

        $this->assertCount(0, $response->getErrors());

        $this->assertCount(2, $response);
        $this->assertCount(2, $response->getResults());

        $result = $response->getResults()[0];

        $this->assertTrue($result->getStatus());
        $this->assertSame(20, $result->getPercentVAT());
    }

    public function test_it_finds_errors()
    {
        $response = $this->getSerializer()->deserialize(FixtureLoader::load('CalculationRequestTariffListResponseError.json'), CalculationWithTariffListResponse::class, 'json');

        /** @var $response CalculationWithTariffListResponse */
        $this->assertInstanceOf(CalculationWithTariffListResponse::class, $response);

        $this->assertFalse($response->hasErrors());

        $this->assertCount(0, $response->getErrors());

        $this->assertCount(1, $response);
        $this->assertCount(1, $response->getResults());

        $result = $response->getResults()[0];

        $this->assertFalse($result->getStatus());

        foreach ($response->getMessages() as $message) {
            /** @var HasErrorCode $message */
            $this->assertSame('3', $message->getErrorCode());
        }
    }

    public function test_it_errors_on_unknown_method_within_a_result()
    {
        $response = $this->getSerializer()->deserialize(FixtureLoader::load('CalculationRequestTariffListResponse.json'), CalculationWithTariffListResponse::class, 'json');

        $this->expectException(\BadMethodCallException::class);
        \call_user_func([$response->getResults()[0], 'foo']);
    }

    public function test_it_serializes_to_empty_json()
    {
        $response = new CalculationWithTariffListResponse();
        $this->assertSame([], $response->jsonSerialize());
    }
}
