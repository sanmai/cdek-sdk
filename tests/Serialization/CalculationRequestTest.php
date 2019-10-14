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

namespace Tests\CdekSDK\Serialization;

use CdekSDK\Contracts\ShouldAuthorize;
use CdekSDK\Requests\CalculationAuthorizedRequest;
use CdekSDK\Requests\CalculationRequest;
use PHPUnit\Framework\TestCase;

/**
 * @covers \CdekSDK\Requests\CalculationRequest
 * @covers \CdekSDK\Requests\CalculationAuthorizedRequest
 * @covers \CdekSDK\Requests\Templates\CalculationAuthorizedRequest
 */
class CalculationRequestTest extends TestCase
{
    public function test_example()
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

        $this->assertSame([
            'version' => '1.0',
            'goods'   => [
                [
                    'weight' => 0.2,
                    'length' => 25,
                    'width'  => 15,
                    'height' => 10,
                ],
            ],
            'tariffId'             => 1,
            'senderCityPostCode'   => '295000',
            'receiverCityPostCode' => '652632',
        ], $request->getBody());
    }

    public function test_with_currency()
    {
        $request = new CalculationRequest();

        $request->setSenderCityPostCode('295000')
            ->setReceiverCityPostCode('652632')
            ->addTariffToList(1)
            ->addTariffToList(8)
            ->addPackage([
                'weight' => 0.2,
                'length' => 25,
                'width'  => 15,
                'height' => 10,
            ]);

        $this->assertSame([
            'version' => '1.0',
            'goods'   => [
                [
                    'weight' => 0.2,
                    'length' => 25,
                    'width'  => 15,
                    'height' => 10,
                ],
            ],
            'tariffList' => [
                [
                    'id' => 1,
                ],
                [
                    'id' => 8,
                ],
            ],
            'senderCityPostCode'   => '295000',
            'receiverCityPostCode' => '652632',
        ], $request->jsonSerialize());
    }

    public function test_with_authorization()
    {
        $request = CalculationRequest::withAuthorization();
        $this->assertInstanceOf(ShouldAuthorize::class, $request);

        if ($request instanceof ShouldAuthorize) {
            $request->date(new \DateTimeImmutable('2018-01-01'));
            $request->credentials('foo', 'bar');
        }

        $request = $request->setSenderCityPostCode('295000')
        ->setReceiverCityPostCode('652632')
        ->setTariffId(1)
        ->addPackage([
            'weight' => 0.2,
            'length' => 25,
            'width'  => 15,
            'height' => 10,
        ]);

        $this->assertSame([
            'version' => '1.0',
            'goods'   => [
                [
                    'weight' => 0.2,
                    'length' => 25,
                    'width'  => 15,
                    'height' => 10,
                ],
            ],
            'tariffId'             => 1,
            'senderCityPostCode'   => '295000',
            'receiverCityPostCode' => '652632',
            'secure'               => 'bar',
            'authLogin'            => 'foo',
            'dateExecute'          => '2018-01-01T00:00:00+00:00',
        ], $request->getBody());
    }

    public function test_with_city_id()
    {
        $request = new CalculationRequest();

        $request = $request->setSenderCityId(1)
            ->setReceiverCityId(2)
            ->setModeId(3)
            ->addAdditionalService(4)
            ->addAdditionalService(5, 6)
            ->addTariffToList(7, 8);

        $this->assertSame([
            'version'    => '1.0',
            'modeId'     => 3,
            'tariffList' => [
                0 => [
                    'id'       => 7,
                    'priority' => 8,
                ],
            ],
            'senderCityId' => 1,
            'services'     => [
                0 => [
                    'id'    => 4,
                    'param' => null,
                ],
                1 => [
                    'id'    => 5,
                    'param' => 6,
                ],
            ],
            'receiverCityId' => 2,
        ], $request->getBody());
    }

    public function test_with_currency_and_date()
    {
        $request = new CalculationAuthorizedRequest();

        $request->credentials('foo', 'bar');
        $request->setDateExecute(new \DateTime('2019-04-08'))->date($request->getRequestDate());

        $request = $request->setCurrency('EUR');

        $this->assertSame([
            'version'     => '1.0',
            'currency'    => 'EUR',
            'dateExecute' => '2019-04-08T00:00:00+00:00',
            'secure'      => 'bar',
            'authLogin'   => 'foo',
        ], $request->jsonSerialize());
    }

    public function test_anonymous_request()
    {
        $request = new CalculationRequest();

        $request->credentials('foo', 'bar');
        $request->setDateExecute(new \DateTime('2019-04-08'))->date($request->getRequestDate());

        $request = $request->setCurrency('EUR');

        $this->assertSame([
            'version'     => '1.0',
            'currency'    => 'EUR',
            'dateExecute' => '2019-04-08T00:00:00+00:00',
        ], $request->jsonSerialize());
    }

    public function test_constants_exists()
    {
        foreach ([
            CalculationRequest::SERVICE_INSURANCE,
            CalculationRequest::SERVICE_DANGEROUS_GOODS,
            CalculationRequest::SERVICE_PICKUP,
            CalculationRequest::SERVICE_DELIVERY_TO_DOOR,
            CalculationRequest::SERVICE_PACKAGE_1,
            CalculationRequest::SERVICE_PACKAGE_2,
            CalculationRequest::SERVICE_TRY_AT_HOME,
            CalculationRequest::SERVICE_PERSONAL_DELIVERY,
            CalculationRequest::SERVICE_DOCUMENTS_COPY,
            CalculationRequest::SERVICE_PARTIAL_DELIVERY,
            CalculationRequest::SERVICE_CARGO_CHECK,
            CalculationRequest::MODE_DOOR_DOOR,
            CalculationRequest::MODE_DOOR_WAREHOUSE,
            CalculationRequest::MODE_WAREHOUSE_DOOR,
            CalculationRequest::MODE_WAREHOUSE_WAREHOUSE,
        ] as $value) {
            $this->assertNotNull($value);
        }
    }
}
