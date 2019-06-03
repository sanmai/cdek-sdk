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

use CdekSDK\Responses\CalculationResponse;
use CdekSDK\Responses\Types\AdditionalService;
use Tests\CdekSDK\Fixtures\FixtureLoader;

/**
 * @covers \CdekSDK\Responses\CalculationResponse
 * @covers \CdekSDK\Responses\Types\Result
 * @covers \CdekSDK\Responses\Types\Error
 * @covers \CdekSDK\Responses\Types\AdditionalService
 */
class CalculationResponseTest extends TestCase
{
    public function test_it_deserializes_errors()
    {
        $response = $this->getSerializer()->deserialize(FixtureLoader::load('CalculationResponseError.json'), CalculationResponse::class, 'json');

        /** @var $response CalculationResponse */
        $this->assertInstanceOf(CalculationResponse::class, $response);

        $this->assertTrue($response->hasErrors());

        $this->assertCount(1, $response->getErrors());
        foreach ($response->getErrors() as $error) {
            $this->assertSame('Не задан тариф или список тарифов', $error->getText());
            $this->assertSame(6, $error->getCode());

            $this->assertSame('Не задан тариф или список тарифов', $error->getMessage());
            $this->assertSame('6', $error->getErrorCode());
        }

        $this->assertCount(1, $response->getMessages());
        foreach ($response->getMessages() as $error) {
            $this->assertSame('Не задан тариф или список тарифов', $error->getMessage());
            $this->assertSame('6', $error->getErrorCode());
        }
    }

    public function test_it_throws_exception()
    {
        $response = $this->getSerializer()->deserialize(FixtureLoader::load('CalculationResponseError.json'), CalculationResponse::class, 'json');

        /** @var $response CalculationResponse */
        $this->assertInstanceOf(CalculationResponse::class, $response);

        $this->expectException(\RuntimeException::class);
        $response->getPrice();
    }

    public function test_it_passes_calls_to_result()
    {
        $response = $this->getSerializer()->deserialize(FixtureLoader::load('CalculationResponse.json'), CalculationResponse::class, 'json');

        /** @var $response CalculationResponse */
        $this->assertInstanceOf(CalculationResponse::class, $response);

        $this->assertFalse($response->hasErrors());
        $this->assertCount(0, $response->getErrors());
        foreach ($response->getErrors() as $error) {
            $this->fail($error->getMessage());
        }

        $this->assertCount(0, $response->getMessages());
        foreach ($response->getMessages() as $error) {
            $this->fail($error->getMessage());
        }

        $this->assertSame($response->getResult()->getPrice(), $response->getPrice());
        $this->assertSame(1250.0, $response->getPrice());
        $this->assertSame(2, $response->getDeliveryPeriodMin());
        $this->assertSame(4, $response->getDeliveryPeriodMax());
        $this->assertSame(1, $response->getTariffId());
        $this->assertSame(1250.0, $response->getPriceByCurrency());
        $this->assertSame('RUB', $response->getCurrency());
        $this->assertSame('2018-01-01', $response->getDeliveryDateMin()->format('Y-m-d'));
        $this->assertSame('2018-01-02', $response->getDeliveryDateMax()->format('Y-m-d'));
        $this->assertSame([], $response->getAdditionalServices());
    }

    public function test_it_loads_additional_services()
    {
        $response = $this->getSerializer()->deserialize(FixtureLoader::load('CalculationResponseWithServices.json'), CalculationResponse::class, 'json');

        /** @var $response CalculationResponse */
        $this->assertInstanceOf(CalculationResponse::class, $response);

        $this->assertFalse($response->hasErrors());
        foreach ($response->getErrors() as $error) {
            $this->fail($error->getMessage());
        }

        $this->assertSame(2, $response->getAdditionalServices()[0]->getId());
        $this->assertSame('Страхование', $response->getAdditionalServices()[0]->getTitle());
        $this->assertEquals(15, $response->getAdditionalServices()[0]->getPrice());

        $this->assertSame(30, $response->getAdditionalServices()[1]->getId());
        $this->assertSame('Примерка на дому', $response->getAdditionalServices()[1]->getTitle());
        $this->assertEquals(0, $response->getAdditionalServices()[1]->getPrice());

        $this->assertCount(2, $response->getAdditionalServices());
    }

    public function test_it_loads_additional_services_extended()
    {
        $response = $this->getSerializer()->deserialize(FixtureLoader::load('CalculationResponseAuthorized.json'), CalculationResponse::class, 'json');

        /** @var $response CalculationResponse */
        $this->assertInstanceOf(CalculationResponse::class, $response);

        $this->assertFalse($response->hasErrors());
        foreach ($response->getErrors() as $error) {
            $this->fail($error->getMessage());
        }

        $this->assertCount(1, $response->getAdditionalServices());

        foreach ($response->getAdditionalServices() as $service) {
            $this->assertInstanceOf(AdditionalService::class, $service);

            $this->assertSame(2, $service->getId());
            $this->assertSame('Страхование', $service->getTitle());
            $this->assertSame(500.0, $service->getPrice());

            $this->assertSame(2, $service->getServiceCode());
            $this->assertSame(500.0, $service->getSum());

            $this->assertSame(1.8, $service->getRate());
        }
    }

    public function test_it_errors_on_unknown_method_within_a_result()
    {
        $response = $this->getSerializer()->deserialize(FixtureLoader::load('CalculationResponse.json'), CalculationResponse::class, 'json');

        $this->expectException(\BadMethodCallException::class);
        \call_user_func([$response, 'foo']);
    }

    public function test_it_errors_on_unknown_method()
    {
        $response = new CalculationResponse();

        $this->expectException(\BadMethodCallException::class);
        \call_user_func([$response, 'foo']);
    }

    public function test_it_serializes_to_empty_json()
    {
        $response = new CalculationResponse();
        $this->assertSame([], $response->jsonSerialize());
    }
}
