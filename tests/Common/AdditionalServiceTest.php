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

use CdekSDK\Common\AdditionalService;

/**
 * @covers \CdekSDK\Common\AdditionalService
 */
class AdditionalServiceTest extends TestCase
{
    public function test_single_argument_create()
    {
        $service = AdditionalService::create(123);
        $this->assertSame(123, $service->getServiceCode());

        $service = AdditionalService::create('123');
        $this->assertSame(123, $service->getServiceCode());
    }

    public function test_fillable_create()
    {
        $service = AdditionalService::create([
            'ServiceCode' => 1234,
            'Sum'         => 500.1,
        ]);

        $this->assertSame(1234, $service->getServiceCode());
        $this->assertSame(500.1, $service->getSum());

        $service = AdditionalService::create([
            'ServiceCode' => '10',
            'Sum'         => '3.14',
        ]);

        $this->assertSame(10, $service->getServiceCode());
        $this->assertSame(3.14, $service->getSum());
    }

    public function test_constants_exists()
    {
        foreach ([
            AdditionalService::SERVICE_INSURANCE,
            AdditionalService::SERVICE_DANGEROUS_GOODS,
            AdditionalService::SERVICE_PICKUP,
            AdditionalService::SERVICE_DELIVERY_TO_DOOR,
            AdditionalService::SERVICE_PACKAGE_1,
            AdditionalService::SERVICE_PACKAGE_2,
            AdditionalService::SERVICE_TRY_AT_HOME,
            AdditionalService::SERVICE_PERSONAL_DELIVERY,
            AdditionalService::SERVICE_DOCUMENTS_COPY,
            AdditionalService::SERVICE_PARTIAL_DELIVERY,
            AdditionalService::SERVICE_CARGO_CHECK,
        ] as $value) {
            $this->assertNotNull($value);
        }
    }
}
