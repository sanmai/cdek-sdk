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

use CdekSDK\Requests\CitiesRequest;
use CdekSDK\Responses\CitiesResponse;

/**
 * @covers \CdekSDK\Requests\CitiesRequest
 * @covers \CdekSDK\Responses\CitiesResponse
 *
 * @group integration
 */
class CitiesRequestTest extends TestCase
{
    public function test_example()
    {
        $request = new CitiesRequest();
        $request->setPage(0)->setSize(1);

        /** @var \CdekSDK\Responses\CitiesResponse $response */
        $response = $this->getClient()->sendCitiesRequest($request);

        $this->skipIfKnownAPIErrorCode($response);

        $this->assertFalse($response->hasErrors());
        $this->assertInstanceOf(CitiesResponse::class, $response);

        $this->assertCount(1, $response->getItems());
        $this->assertCount(1, $response);

        $location = $response->getItems()[0];

        $this->assertNotEmpty($location->getName());
    }

    public function test_with_everything()
    {
        $request = new CitiesRequest();
        $request = $request->setRegionCodeExt(1);
        $request = $request->setRegionCode(2);
        $request = $request->setRegionFiasGuid(3);
        $request = $request->setPage(6);
        $request = $request->setSize(7);
        $request = $request->setPostcode('111222');

        $response = $this->getClient()->sendCitiesRequest($request);

        $this->skipIfKnownAPIErrorCode($response);

        $this->assertFalse($response->hasErrors());
        $this->assertCount(0, $response->getItems());
    }

    public function test_found_city_by_name()
    {
        $request = new CitiesRequest();
        $request = $request->setPage(0)->setSize(1);
        $request = $request->setCountryCode('RU');
        $request = $request->setCityName('Новосибирск');

        $response = $this->getClient()->sendCitiesRequest($request);

        $this->skipIfKnownAPIErrorCode($response);

        $this->assertFalse($response->hasErrors());
        $this->assertCount(1, $response->getItems());

        foreach ($response as $item) {
            $this->assertSame('Новосибирск', $item->getCityName());
            $this->assertSame('Asia/Novosibirsk', $item->getTimezone());
        }
    }

    public function test_with_null_payment_limit()
    {
        $request = new CitiesRequest();
        $request = $request->setPage(0)->setSize(5);
        $request->setRegionCode(9);

        $response = $this->getClient()->sendCitiesRequest($request);

        $this->skipIfKnownAPIErrorCode($response);

        $this->assertFalse($response->hasErrors());

        foreach ($response as $item) {
            $this->assertTrue(\is_float($item->getPaymentLimit()));
        }
    }
}
