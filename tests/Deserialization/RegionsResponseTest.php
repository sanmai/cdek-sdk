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

use CdekSDK\Common\Region;
use CdekSDK\Responses\RegionsResponse;
use Tests\CdekSDK\Fixtures\FixtureLoader;

/**
 * @covers \CdekSDK\Common\Region
 * @covers \CdekSDK\Responses\RegionsResponse
 */
class RegionsResponseTest extends TestCase
{
    public function test_it_deserializes()
    {
        $response = $this->getSerializer()->deserialize(FixtureLoader::load('Regions.xml'), RegionsResponse::class, 'xml');

        /** @var $response RegionsResponse */
        $this->assertFalse($response->hasErrors());

        $this->assertInstanceOf(RegionsResponse::class, $response);

        $this->assertNotEmpty($response->getItems());

        $this->assertCount(5, $response->getItems());
        $this->assertCount(5, $response);

        foreach ($response->getItems() as $region) {
            $this->assertInstanceOf(Region::class, $region);
        }

        $region = $response->getItems()[0];
        /** @var $region Region */
        $this->assertSame('18aff43f-58b8-4608-ade7-92fdab7fc39f', $region->getUuid());
        $this->assertSame('Тверская', $region->getName());
        $this->assertSame('обл', $region->getPrefix());
        $this->assertSame(50, $region->getCode());
        $this->assertSame(69, $region->getCodeExt());
        $this->assertSame('61723327-1c20-42fe-8dfa-402638d9b396', $region->getFiasGuid());
        $this->assertSame('РОССИЯ', $region->getCountryName());
        $this->assertSame(1, $region->getCountryCode());
        $this->assertSame(643, $region->getCountryCodeExt());

        foreach ($response as $item) {
            break;
        }

        assert(isset($item));
        $this->assertSame($region, $item);
    }

    public function test_it_has_no_errors()
    {
        $response = new RegionsResponse();
        $this->assertFalse($response->hasErrors());
        $this->assertCount(0, $response->getMessages());
    }

    public function test_it_serializes_to_empty_json()
    {
        $response = new RegionsResponse();
        $this->assertSame([], $response->jsonSerialize());
    }
}
