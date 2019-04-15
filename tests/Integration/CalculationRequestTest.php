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

use CdekSDK\Requests\CalculationRequest;

/**
 * @covers \CdekSDK\Requests\CalculationRequest
 * @covers \CdekSDK\Responses\CalculationResponse
 *
 * @group integration
 */
class CalculationRequestTest extends TestCase
{
    const UNAUTHORIZED_ERROR = 2;

    public function test_success()
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

        $response = $this->getClient()->sendCalculationRequest($request);

        foreach ($response->getErrors() as $error) {
            $this->assertEmpty($error->getMessage());
            $this->fail($error->getErrorCode());
        }

        $this->assertFalse($response->hasErrors());

        /** @var \CdekSDK\Responses\CalculationResponse $response */
        $this->assertGreaterThan(0, $response->getPrice());
    }

    public function test_authorized_success()
    {
        $request = CalculationRequest::withAuthorization()
        ->setSenderCityPostCode('295000')
        ->setReceiverCityPostCode('652632')
        ->addAdditionalService(CalculationRequest::SERVICE_INSURANCE, 2000)
        ->setTariffId(1)
        ->addPackage([
            'weight' => 0.2,
            'length' => 25,
            'width'  => 15,
            'height' => 10,
        ]);

        $response = $this->getClient()->sendCalculationRequest($request);

        foreach ($response->getErrors() as $error) {
            if ((int) $error->getErrorCode() === self::UNAUTHORIZED_ERROR) {
                $this->skipIfTestEndpointIsUsed("{$error->getErrorCode()}: {$error->getMessage()}");
            }

            $this->fail("{$error->getErrorCode()}: {$error->getMessage()}");
        }

        $this->assertFalse($response->hasErrors());
        $this->assertCount(1, $response->getAdditionalServices());

        /** @var \CdekSDK\Responses\CalculationResponse $response */
        $this->assertGreaterThan(0, $response->getPrice());
    }

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
}
