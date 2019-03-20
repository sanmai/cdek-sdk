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

        $this->assertSame('0c4563e7-3465-4325-a6d8-be0e06a1ab48', $region->getUuid(), sprintf('Received unexpected region: %s', var_export($region, true)));
        $this->assertSame('Хабаровский', $region->getName());
        $this->assertSame('край', $region->getPrefix());
        $this->assertSame(14, $region->getCode());
        $this->assertSame(27, $region->getCodeExt());
        $this->assertSame('7d468b39-1afa-41ec-8c4f-97a8603cb3d4', $region->getFiasGuid());
        $this->assertSame('Russia', $region->getCountryName());
        $this->assertSame(643, $region->getCountryCodeExt());
        $this->assertSame('RU', $region->getCountryCodeISO());
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
