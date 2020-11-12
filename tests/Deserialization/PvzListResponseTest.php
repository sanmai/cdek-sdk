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

use CdekSDK\Common\Pvz;
use CdekSDK\Responses\PvzListResponse;
use Tests\CdekSDK\Fixtures\FixtureLoader;

/**
 * @covers \CdekSDK\Responses\PvzListResponse
 * @covers \CdekSDK\Common\Pvz
 * @covers \CdekSDK\Common\OfficeImage
 * @covers \CdekSDK\Common\PhoneDetail
 * @covers \CdekSDK\Common\WorkTime
 */
class PvzListResponseTest extends TestCase
{
    public function test_empty_response()
    {
        $response = $this->getSerializer()->deserialize(FixtureLoader::load('PvzListEmpty.xml'), PvzListResponse::class, 'xml');

        /** @var $response PvzListResponse */
        $this->assertInstanceOf(PvzListResponse::class, $response);
        $this->assertEmpty($response->getItems());
    }

    public function test_common_response()
    {
        $response = $this->getSerializer()->deserialize(FixtureLoader::load('PvzList.xml'), PvzListResponse::class, 'xml');

        /** @var $response PvzListResponse */
        $this->assertInstanceOf(PvzListResponse::class, $response);
        $this->assertNotEmpty($response->getItems());
        $this->assertCount(3, $response->getItems());
        $this->assertCount(3, $response);

        foreach ($response as $item) {
            $this->assertInstanceOf(Pvz::class, $item);
        }

        $item = $response->getItems()[0];
        $this->assertInstanceOf(Pvz::class, $item);

        $this->assertIsBool($item->HaveCashless);
        $this->assertTrue($item->HaveCashless);

        $this->assertIsBool($item->HaveCash);
        $this->assertTrue($item->HaveCash);

        $this->assertIsBool($item->TakeOnly);
        $this->assertFalse($item->TakeOnly);

        /** @var $item Pvz */
        $this->assertSame('EKB8', $item->Code);

        $item = $response->getItems()[2];
        $this->assertSame('RU', $item->CountryCodeISO);
        $this->assertCount(1, $item->OfficeImages);
        $this->assertStringStartsWith('http', $item->OfficeImages[0]->getUrl());
    }

    public function test_new_true_false()
    {
        $response = $this->getSerializer()->deserialize(FixtureLoader::load('PvzListFalseTrue.xml'), PvzListResponse::class, 'xml');

        /** @var $response PvzListResponse */
        $this->assertInstanceOf(PvzListResponse::class, $response);

        $this->assertNotEmpty($response->getItems());
        $this->assertCount(1, $response->getItems());
        $this->assertCount(1, $response);

        $item = $response->getItems()[0];
        $this->assertInstanceOf(Pvz::class, $item);

        $this->assertIsBool($item->HaveCashless);
        $this->assertTrue($item->HaveCashless);
    }

    public function test_it_reads_chinese_list()
    {
        $response = $this->getSerializer()->deserialize(FixtureLoader::load('PvzListCN.xml'), PvzListResponse::class, 'xml');

        /** @var $response PvzListResponse */
        $this->assertInstanceOf(PvzListResponse::class, $response);
        $this->assertNotEmpty($response->getItems());
        $this->assertCount(6, $response->getItems());
        $this->assertCount(6, $response);

        $item = $response->getItems()[0];
        $this->assertInstanceOf(Pvz::class, $item);

        $this->assertSame('SHAN4', $item->Code);

        $this->assertSame('SHAN4', $item->Code);
        $this->assertSame('200001', $item->PostalCode);
        $this->assertSame('黄浦', $item->Name);
        $this->assertSame(138, $item->CountryCode);
        $this->assertSame('中国', $item->CountryName);
        $this->assertSame(906, $item->RegionCode);
        $this->assertSame('上海', $item->RegionName);
        $this->assertSame(12683, $item->CityCode);
        $this->assertSame('上海市', $item->City);
        $this->assertSame('周一至周五 09:00-17:30', $item->WorkTime);
        $this->assertSame('青浦区北青公路3688号H', $item->Address);
        $this->assertSame('中国上海上海市青浦区北青公路3688号H', $item->FullAddress);
        $this->assertSame('', $item->AddressComment);
        $this->assertSame('+8613661716712', $item->Phone);
        $this->assertSame('Shuchen@cdek.ru', $item->Email);
        $this->assertSame('testing@qq', $item->qqId);
        $this->assertSame('', $item->Note);
        $this->assertSame(121.479642, $item->coordX);
        $this->assertSame(31.245725, $item->coordY);
        $this->assertSame('PVZ', $item->Type);
        $this->assertSame('cdek', $item->ownerCode);
        $this->assertTrue($item->IsDressingRoom);
        $this->assertTrue($item->HaveCashless);
        $this->assertTrue($item->HaveCash);
        $this->assertTrue($item->AllowedCod);
        $this->assertSame('', $item->NearestStation);
        $this->assertSame('', $item->MetroStation);
        $this->assertSame('', $item->Site);

        $this->assertSame('+8613661716712', $item->phoneDetails[0]->getNumber());
        $this->assertSame('+8613661716712', (string) $item->phoneDetails[0]);

        $this->assertSame(5, $item->OfficeImages[4]->number);
        $this->assertSame('http://172.16.100.4:8008/images/2074/2008_5_SHAN4', $item->OfficeImages[4]->getUrl());
        $this->assertSame('http://172.16.100.4:8008/images/2074/2008_5_SHAN4', (string) $item->OfficeImages[4]);

        $this->assertSame(5, $item->workTimes[4]->getDay());
        $this->assertSame('09:00/17:30', $item->workTimes[4]->getPeriods());

        $this->assertIsBool($item->HaveCashless);
        $this->assertTrue($item->HaveCashless);

        $this->assertIsBool($item->HaveCash);
        $this->assertTrue($item->HaveCash);
    }

    public function test_it_reads_version_circa_2020_11()
    {
        $response = $this->getSerializer()->deserialize(FixtureLoader::load('PvzList2020.xml'), PvzListResponse::class, 'xml');

        /** @var $response PvzListResponse */
        $this->assertInstanceOf(PvzListResponse::class, $response);
        $this->assertNotEmpty($response->getItems());
        $this->assertCount(1, $response->getItems());
        $this->assertCount(1, $response);

        $item = $response->getItems()[0];
        $this->assertInstanceOf(Pvz::class, $item);

        $this->assertSame('YEKB34', $item->Code);

        $this->assertIsBool($item->IsHandout);
        $this->assertTrue($item->IsHandout);
    }

    public function test_it_has_no_errors()
    {
        $response = new PvzListResponse();
        $this->assertFalse($response->hasErrors());
        $this->assertCount(0, $response->getMessages());
    }

    public function test_it_serializes_to_empty_json()
    {
        $response = new PvzListResponse();
        $this->assertSame([], $response->jsonSerialize());
    }
}
