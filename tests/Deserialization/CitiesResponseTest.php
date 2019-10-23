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

use CdekSDK\Common\Location;
use CdekSDK\Responses\CitiesResponse;
use Tests\CdekSDK\Fixtures\FixtureLoader;

/**
 * @covers \CdekSDK\Common\Location
 * @covers \CdekSDK\Responses\CitiesResponse
 */
class CitiesResponseTest extends TestCase
{
    public function test_it_deserializes()
    {
        $response = $this->getSerializer()->deserialize(FixtureLoader::load('CitiesResponse.xml'), CitiesResponse::class, 'xml');

        /** @var $response CitiesResponse */
        $this->assertInstanceOf(CitiesResponse::class, $response);

        $this->assertFalse($response->hasErrors());
        $this->assertCount(0, $response->getMessages());

        $this->assertNotEmpty($response->getItems());

        $this->assertCount(5, $response->getItems());
        $this->assertCount(5, $response);

        foreach ($response->getItems() as $location) {
            $this->assertInstanceOf(Location::class, $location);
        }

        $location = $response->getItems()[0];

        /** @var $location Location */
        $this->assertSame('Лебедевка', $location->getName());
        $this->assertSame('Лебедевка', $location->getCityName());
        $this->assertSame(37960, $location->getCityCode());
        $this->assertSame('1ef4f958-43c9-4a80-9fd6-e414231c3e55', $location->getCityUuid());
        $this->assertSame('РОССИЯ', $location->getCountry());
        $this->assertSame(1, $location->getCountryCode());
        $this->assertSame('RU', $location->getCountryCodeISO());
        $this->assertSame('Новосибирская', $location->getRegion());
        $this->assertSame(23, $location->getRegionCode());
        $this->assertSame(54, $location->getRegionCodeExt());
        $this->assertSame('Искитимский', $location->getSubRegion());
        $this->assertSame(-1.0, $location->getPaymentLimit());
        $this->assertSame(54.6711, $location->getLatitude());
        $this->assertSame(83.2337, $location->getLongitude());
        $this->assertSame('5400800007900', $location->getKladr());

        $this->assertSame('873b2cc6-cf4e-4001-aca7-eb0380951bfc', $location->getFiasGuid());

        foreach ($response as $item) {
            break;
        }

        \assert(isset($item));
        $this->assertSame($location, $item);
    }

    public function test_it_works_around_missing_payment_limit()
    {
        $response = $this->getSerializer()->deserialize(FixtureLoader::load('CitiesResponseNoPaymentLimit.xml'), CitiesResponse::class, 'xml');

        $this->assertFalse($response->hasErrors());

        foreach ($response as $item) {
            $this->assertTrue(\is_float($item->getPaymentLimit()));
        }
    }

    public function test_it_works_around_literal_contry_code()
    {
        $response = $this->getSerializer()->deserialize(FixtureLoader::load('CitiesResponseWithLiteralCountryCode.xml'), CitiesResponse::class, 'xml');

        $this->assertFalse($response->hasErrors());
        $this->assertCount(1, $response);

        $location = $response->getItems()[0];
        /** @var $location Location */
        $this->assertSame(1, $location->getCountryCode());
        $this->assertSame('RU', $location->getCountryCodeISO());
    }

    public function test_it_handles_known_country_codes()
    {
        $location = $this->getSerializer()->deserialize('<Location countryCode="RU" />', Location::class, 'xml');

        $this->assertSame(1, $location->getCountryCode());
        $this->assertSame('RU', $location->getCountryCodeISO());

        $location = $this->getSerializer()->deserialize('<Location countryCode="1" />', Location::class, 'xml');

        $this->assertSame(1, $location->getCountryCode());
        $this->assertSame('RU', $location->getCountryCodeISO());
    }

    public function test_it_handles_unrecognized_country_code()
    {
        $location = $this->getSerializer()->deserialize('<Location countryCode="20" />', Location::class, 'xml');

        $this->assertSame(20, $location->getCountryCode());
        $this->assertSame('', $location->getCountryCodeISO());

        $location = $this->getSerializer()->deserialize('<Location countryCode="AA" />', Location::class, 'xml');

        $this->assertSame(0, $location->getCountryCode());
        $this->assertSame('AA', $location->getCountryCodeISO());
    }

    public function test_it_reads_timezone()
    {
        $response = $this->getSerializer()->deserialize(FixtureLoader::load('CitiesResponseWithTimezone.xml'), CitiesResponse::class, 'xml');

        $this->assertFalse($response->hasErrors());
        $this->assertCount(1, $response);

        $location = $response->getItems()[0];
        /** @var $location Location */
        $this->assertSame('Новосибирск', $location->getName());
        $this->assertSame('Asia/Novosibirsk', $location->getTimezone());
    }

    public function test_it_serializes_to_empty_json()
    {
        $response = new CitiesResponse();
        $this->assertSame([], $response->jsonSerialize());
    }
}
