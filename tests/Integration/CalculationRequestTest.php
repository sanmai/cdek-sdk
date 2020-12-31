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

use CdekSDK\Common\AdditionalService;
use CdekSDK\Contracts\Response;
use CdekSDK\Requests\CalculationRequest;
use Tests\CdekSDK\Integration\Requests\CalculationAuthorizedRequest;

/**
 * @covers \CdekSDK\Requests\CalculationRequest
 * @covers \CdekSDK\Requests\CalculationAuthorizedRequest
 * @covers \CdekSDK\Responses\CalculationResponse
 *
 * @group integration
 */
class CalculationRequestTest extends TestCase
{
    const UNAUTHORIZED_ERROR = 2;
    const UNAVAILABLE_DESTINATION_ERROR = 3;

    public function test_success_anonymous()
    {
        $request = new CalculationRequest();
        $request->setSenderCityPostCode('295000')
        ->setReceiverCityPostCode('652632')
        ->setTariffId(1)
        ->addPackage([
            'weight' => 0.2,
            'length' => 25,
            'width'  => 15,
            'height' => 10,
        ]);

        $response = $this->getAnonymousClient()->sendCalculationRequest($request);

        foreach ($response->getErrors() as $error) {
            $this->assertEmpty($error->getMessage());
            $this->fail($error->getErrorCode());
        }

        $this->assertFalse($response->hasErrors());

        /** @var \CdekSDK\Responses\CalculationResponse $response */
        $this->assertGreaterThan(0, $response->getPrice());
    }

    public function test_with_authorisation_returns()
    {
        $this->assertInstanceOf(\CdekSDK\Requests\CalculationAuthorizedRequest::class, CalculationRequest::withAuthorization());
    }

    /**
     * @psalm-suppress ArgumentTypeCoercion
     */
    public function test_authorized_success()
    {
        $request = new CalculationAuthorizedRequest();
        $request->setSenderCityPostCode('295000')
        ->setReceiverCityPostCode('652632')
        ->addAdditionalService(AdditionalService::SERVICE_INSURANCE, 2000)
        ->setTariffId(1)
        ->addPackage([
            'weight' => 0.2,
            'length' => 25,
            'width'  => 15,
            'height' => 10,
        ]);

        $response = $this->getClient()->sendCalculationRequest($request);

        $this->assertNoErrors($response);

        $this->assertFalse($response->hasErrors());
        $this->assertCount(1, $response->getAdditionalServices());

        /** @var \CdekSDK\Responses\CalculationResponse $response */
        $this->assertGreaterThan(0, $response->getPrice());

        $this->assertNull($response->getCashOnDelivery());
    }

    /**
     * @psalm-suppress ArgumentTypeCoercion
     */
    public function test_failure()
    {
        $request = (new CalculationRequest())
        ->setSenderCityPostCode('295000')
        ->setReceiverCityPostCode('652632')
        ->addPackage([
            'weight' => 0.2,
            'length' => 25,
            'width'  => 15,
            'height' => 10,
        ]);

        $response = $this->getClient()->sendCalculationRequest($request);

        /** @var \CdekSDK\Responses\CalculationResponse $response */
        $this->assertTrue($response->hasErrors());

        foreach ($response->getErrors() as $error) {
            $this->assertGreaterThan(0, (int) $error->getErrorCode());
            $this->assertNotEmpty($error->getMessage());
        }
    }

    public function test_authorized_cash_on_delivery()
    {
        $request = new CalculationAuthorizedRequest();
        $request->setSenderCityId(288)
                ->setReceiverCityId(2766)
                ->setTariffId(1)
                ->addPackage([
                    'weight' => 1,
                    'length' => 4,
                    'width'  => 4,
                    'height' => 4,
                ]);

        $response = $this->getClient()->sendCalculationRequest($request);

        /** @var \CdekSDK\Responses\CalculationResponse $response */
        $this->assertNoErrors($response);

        $this->assertFalse($response->hasErrors());

        $this->assertSame(0.0, $response->getCashOnDelivery());
    }

    public function test_authorized_international()
    {
        $request = new CalculationAuthorizedRequest();
        $request->setSenderCityId(32150)
            ->setReceiverCityId(920)
            ->setTariffId(7)
            ->addPackage([
                'weight' => 1,
                'length' => 4,
                'width'  => 4,
                'height' => 4,
            ]);

        $response = $this->getClient()->sendCalculationRequest($request);

        /** @var \CdekSDK\Responses\CalculationResponse $response */
        foreach ($response->getMessages() as $error) {
            if ((int) $error->getErrorCode() === self::UNAVAILABLE_DESTINATION_ERROR) {
                $this->markTestIncomplete("{$error->getErrorCode()}: {$error->getMessage()}");
            }
        }

        $this->assertNoErrors($response);

        $this->assertFalse($response->hasErrors());

        $this->assertGreaterThan(0.0, $response->getPrice());
    }

    /**
     * Проверяем отправку в Германию из России без кодов городов.
     */
    public function test_authorized_international_cityname()
    {
        $request = new CalculationAuthorizedRequest();
        $request->setSenderCity('Moscow')
        ->setSenderCountryCode('ru')
        ->setReceiverCity('Dresden')
        ->setReceiverCountryCode('de')
        ->setTariffId(8)
            ->addPackage([
                'weight' => 1,
                'length' => 10,
                'width'  => 10,
                'height' => 10,
            ]);

        $response = $this->getClient()->sendCalculationRequest($request);

        $this->assertNoErrors($response);

        $this->assertFalse($response->hasErrors());

        $this->assertGreaterThan(0.0, $response->getPrice());

        // make the same request with CDEK city ids to make sure the results are the same
        $request = new CalculationAuthorizedRequest();
        $request->setSenderCityId(44)
        ->setReceiverCityId(7002)
        ->setTariffId(8)
            ->addPackage([
                'weight' => 1,
                'length' => 10,
                'width'  => 10,
                'height' => 10,
            ]);

        $response2 = $this->getClient()->sendCalculationRequest($request);
        $this->assertNoErrors($response2);
        $this->assertFalse($response2->hasErrors());
        $this->assertEquals($response->getPrice(), $response2->getPrice());
        $this->assertEquals($response->getDeliveryPeriodMin(), $response2->getDeliveryPeriodMin());
        $this->assertEquals($response->getDeliveryPeriodMax(), $response2->getDeliveryPeriodMax());
    }

    private function assertNoErrors(Response $response)
    {
        foreach ($response->getMessages() as $error) {
            $this->fail("{$error->getErrorCode()}: {$error->getMessage()}");
        }
    }
}
