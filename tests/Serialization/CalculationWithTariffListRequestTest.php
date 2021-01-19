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

use CdekSDK\Requests\CalculationWithTariffListRequest;
use PHPUnit\Framework\TestCase;

/**
 * @covers \CdekSDK\Requests\CalculationWithTariffListAuthorizedRequest
 * @covers \CdekSDK\Requests\CalculationWithTariffListRequest
 * @covers \CdekSDK\Requests\Templates\CalculationAuthorizedRequest
 */
class CalculationWithTariffListRequestTest extends TestCase
{
    public function test_example()
    {
        $request = new CalculationWithTariffListRequest();
        $request->setDateExecute(new \DateTime('2019-04-01'));
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

        $this->assertEquals(\json_decode('{
            "version":"1.0",
            "dateExecute":"2019-04-01T00:00:00",
            "senderCityId":"44",
            "receiverCityId":"269",
            "currency":"RUB",
            "tariffList":
                [
                    {
                        "id":1
                    },
                    {
                        "id":8
                    }
                ],
            "goods":
                [
                    {
                        "weight":"1",
                        "length":"1",
                        "width":"2",
                        "height":"7"
                    }
                ],
            "services":
                [
                    {
                        "id":2,
                        "param":1000
                    }
                ]
        }', true), $request->jsonSerialize());
    }

    public function test_set_tariff_adds_to_list()
    {
        $request = new CalculationWithTariffListRequest();

        $request->setTariffId(1)->setTariffId(2);

        $this->assertSame([
            'version'    => '1.0',
            'tariffList' => [
                ['id' => 1],
                ['id' => 2],
            ],
        ], $request->jsonSerialize());
    }
}
