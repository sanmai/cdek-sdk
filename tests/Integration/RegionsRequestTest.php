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

use CdekSDK\Requests\RegionsRequest;
use CdekSDK\Responses\RegionsResponse;

/**
 * @covers \CdekSDK\Requests\RegionsRequest
 * @covers \CdekSDK\Responses\RegionsResponse
 *
 * @group integration
 */
class RegionsRequestTest extends TestCase
{
    public function test_example()
    {
        $request = new RegionsRequest();
        $request->setCountryCode('RU');
        $request->setPage(0)->setSize(1);

        $response = $this->getClient()->sendRegionsRequest($request);

        $this->assertFalse($response->hasErrors());
        $this->assertInstanceOf(RegionsResponse::class, $response);

        $this->assertCount(1, $response->getItems());

        $region = $response->getItems()[0];

        if ($region->getCountryCode() === 0) {
            $this->markTestSkipped("Unknown country: {$region->getCountryName()}");
        }

        $this->assertSame('18aff43f-58b8-4608-ade7-92fdab7fc39f', $region->getUuid());
        $this->assertSame('Тверская', $region->getName());
        $this->assertSame('обл', $region->getPrefix());
        $this->assertSame(50, $region->getCode());
        $this->assertSame(69, $region->getCodeExt());
        $this->assertSame('61723327-1c20-42fe-8dfa-402638d9b396', $region->getFiasGuid());
        $this->assertSame('Россия', $region->getCountryName());
        $this->assertSame(1, $region->getCountryCode());
        $this->assertSame(643, $region->getCountryCodeExt());
    }

    public function test_with_everything()
    {
        $request = new RegionsRequest();
        $request = $request->setRegionCodeExt(1);
        $request = $request->setRegionCode(2);
        $request = $request->setRegionFiasGuid(3);
        $request = $request->setCountryCode(4);
        $request = $request->setCountryCodeExt(5);
        $request = $request->setPage(6);
        $request = $request->setSize(7);

        $response = $this->getClient()->sendRegionsRequest($request);

        $this->assertFalse($response->hasErrors());
        $this->assertCount(0, $response->getItems());
    }
}
