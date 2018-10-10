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

        assert(isset($item));
        $this->assertSame($location, $item);

        $this->assertSameAsJSON('[
    {
        "cityUuid": "1ef4f958-43c9-4a80-9fd6-e414231c3e55",
        "cityName": "Лебедевка",
        "cityCode": 37960,
        "region": "Новосибирская",
        "regionCode": 23,
        "regionCodeExt": 54,
        "subRegion": "Искитимский",
        "country": "РОССИЯ",
        "countryCode": 1,
        "latitude": 54.6711,
        "longitude": 83.2337,
        "kladr": "5400800007900",
        "fiasGuid": "873b2cc6-cf4e-4001-aca7-eb0380951bfc",
        "paymentLimit": -1
    },
    {
        "cityUuid": "228fcc02-be09-411b-8d88-aaab86c79a8f",
        "cityName": "Новопокровский",
        "cityCode": 49876,
        "region": "Новосибирская",
        "regionCode": 23,
        "regionCodeExt": 54,
        "subRegion": "Краснозерский",
        "country": "РОССИЯ",
        "countryCode": 1,
        "latitude": 54.23,
        "longitude": 78.6929,
        "kladr": "5401400004900",
        "fiasGuid": "33836940-2211-4335-ae2e-7244fa88fe9e",
        "paymentLimit": -1
    },
    {
        "cityUuid": "22f58510-3b97-42b4-bd86-6e21a0e74ba9",
        "cityName": "Александро-Невский",
        "cityCode": 1961,
        "region": "Новосибирская",
        "regionCode": 23,
        "regionCodeExt": 54,
        "subRegion": "Баганский",
        "country": "РОССИЯ",
        "countryCode": 1,
        "latitude": 54.0212,
        "longitude": 77.3931,
        "kladr": "5400200002500",
        "fiasGuid": "34cbaf34-7ab2-42a4-b21a-c3d4b3a492e5",
        "paymentLimit": -1
    },
    {
        "cityUuid": "23f5dc61-43b0-4566-9413-344de99e1b11",
        "cityName": "Маршанское",
        "cityCode": 39646,
        "region": "Новосибирская",
        "regionCode": 23,
        "regionCodeExt": 54,
        "subRegion": "Каргатский",
        "country": "РОССИЯ",
        "countryCode": 1,
        "latitude": 55.0366,
        "longitude": 79.8615,
        "kladr": "5401000001200",
        "fiasGuid": "9d27abd6-049c-47ef-9f58-51b7a1c07a58",
        "paymentLimit": -1
    },
    {
        "cityUuid": "574033d7-bfed-45b1-8745-d9917a58e8e4",
        "cityName": "Чистоозерное",
        "cityCode": 752,
        "region": "Новосибирская",
        "regionCode": 23,
        "regionCodeExt": 54,
        "subRegion": "Чистоозерный",
        "country": "РОССИЯ",
        "countryCode": 1,
        "latitude": 54.7074,
        "longitude": 76.5818,
        "kladr": "5402900000100",
        "fiasGuid": "6c5bea6d-2211-49e4-ae75-192fc5e88b20",
        "paymentLimit": -1
    }
]', $response);
    }

    public function test_it_serializes_to_empty_json()
    {
        $response = new CitiesResponse();
        $this->assertSame([], $response->jsonSerialize());
    }
}
