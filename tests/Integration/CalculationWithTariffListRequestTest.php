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
use CdekSDK\Requests\CalculationWithTariffListRequest;
use CdekSDK\Responses\Types\Error;
use Tests\CdekSDK\Integration\Requests\CalculationWithTariffListAuthorizedRequest;

/**
 * @covers \CdekSDK\Requests\CalculationWithTariffListAuthorizedRequest
 * @covers \CdekSDK\Requests\CalculationWithTariffListRequest
 * @covers \CdekSDK\Responses\CalculationResponse
 *
 * @group integration
 */
class CalculationWithTariffListRequestTest extends TestCase
{
    public const UNAUTHORIZED_ERROR = 2;
    public const UNAVAILABLE_DESTINATION_ERROR = 3;

    public function test_success_anonymous()
    {
        $request = new CalculationWithTariffListRequest();
        $request->setSenderCityId(44)
            ->setReceiverCityId(269)
            ->setCurrency('RUB')
            ->addTariffToList(1)
            ->addTariffToList(8)
            ->addPackage([
                'weight' => 1,
                'length' => 1,
                'width'  => 2,
                'height' => 7,
            ])
            ->addAdditionalService(2, 1000);

        /** @var \CdekSDK\Responses\CalculationWithTariffListResponse $response */
        $response = $this->getAnonymousClient()->sendCalculationWithTariffListRequest($request);

        foreach ($response->getErrors() as $error) {
            $this->assertEmpty($error->getMessage());
            $this->fail($error->getErrorCode());
        }

        $this->assertFalse($response->hasErrors());

        $this->assertCount(2, $response);
    }

    public function test_failing_anonymous()
    {
        $request = new CalculationWithTariffListRequest();

        $response = $this->getAnonymousClient()->sendCalculationWithTariffListRequest($request);

        /** @var \CdekSDK\Responses\CalculationWithTariffListResponse $response */
        $this->assertTrue($response->hasErrors());

        /** @var Error $error */
        foreach ($response->getErrors() as $error) {
            $this->assertInstanceOf(Error::class, $error);
            $this->assertGreaterThan(0, $error->getCode());
            $this->assertNotEmpty($error->getMessage());
        }

        $this->assertCount(4, $response->getErrors());
        $this->assertCount(0, $response->getResults());
    }

    /**
     * @psalm-suppress InvalidArgument
     * @psalm-suppress MixedAssignment
     * @psalm-suppress MixedMethodCall
     */
    public function test_authorized_success()
    {
        $request = new CalculationWithTariffListAuthorizedRequest();
        $request->setSenderCityPostCode('295000')
            ->setReceiverCityPostCode('652632')
            ->addAdditionalService(AdditionalService::SERVICE_INSURANCE, 2000)
            ->setTariffId(137)
            ->addPackage([
                'weight' => 0.2,
                'length' => 25,
                'width'  => 15,
                'height' => 10,
            ]);

        /** @var \CdekSDK\Responses\CalculationWithTariffListResponse $response */
        $response = $this->getClient()->sendCalculationRequest($request);

        foreach ($response->getErrors() as $error) {
            if ((int) $error->getErrorCode() === self::UNAVAILABLE_DESTINATION_ERROR) {
                $this->skipIfTestEndpointIsUsed("{$error->getErrorCode()}: {$error->getMessage()}");
            }

            $this->fail("{$error->getErrorCode()}: {$error->getMessage()}");
        }

        $this->assertFalse($response->hasErrors());

        $this->assertCount(1, $response);

        foreach ($response as $result) {
            /** @var \CdekSDK\Responses\Types\TariffResult $result */
            foreach ($result->getErrors() as $error) {
                if ((int) $error->getErrorCode() === self::UNAVAILABLE_DESTINATION_ERROR) {
                    continue 2;
                }
            }

            $this->assertFalse($result->hasErrors());

            $this->assertGreaterThan(0, $result->getPriceByCurrency());
        }
    }
}
