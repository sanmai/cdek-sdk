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

/**
 * @covers \CdekSDK\Common\Pvz
 * @covers \CdekSDK\Common\WeightLimit
 * @covers \CdekSDK\Common\Dimensions
 */
class PvzTest extends TestCase
{
    public function test_simple_pvz()
    {
        $pvz = $this->getSerializer()->deserialize('<Pvz Code="KUR7" PostalCode="640018" Name="На Советской" CountryCode="1" countryCodeIso="RU" CountryName="Россия" RegionCode="28" RegionName="Курганская обл." CityCode="93" City="Курган" WorkTime="Пн-Пт 10:00-19:00, Сб 10:00-16:00" Address="ул. Советская, 128" FullAddress="Россия, Курганская обл., Курган, ул. Советская, 128" AddressComment="Вход с улицы Пичугина" Phone="+79125252100" Email="d.galko@cdek.ru" qqId="" Note="" coordX="65.354189" coordY="55.438706" Type="PVZ" ownerCode="cdek" IsDressingRoom="true" HaveCashless="true" AllowedCod="true" NearestStation="Центральный рынок" MetroStation="" Site="">
    <PhoneDetail number="+79125252100"/>
    <OfficeImage number="1" url="https://pvzimage.cdek.ru/images/1622/1037_1_KUR7"/>
    <OfficeImage number="2" url="https://pvzimage.cdek.ru/images/1622/1038_2_KUR7"/>
    <OfficeImage number="3" url="https://pvzimage.cdek.ru/images/1622/1039_3_KUR7"/>
    <WorkTimeY day="1" periods="10:00/19:00"/>
    <WorkTimeY day="2" periods="10:00/19:00"/>
    <WorkTimeY day="3" periods="10:00/19:00"/>
    <WorkTimeY day="4" periods="10:00/19:00"/>
    <WorkTimeY day="5" periods="10:00/19:00"/>
    <WorkTimeY day="6" periods="10:00/16:00"/>
    <WeightLimit WeightMin="0" WeightMax="30"/>
</Pvz>', Pvz::class, 'xml');

        /** @var $pvz Pvz */
        $this->assertSame('+79125252100', $pvz->Phone);
        $this->assertSame('640018', $pvz->PostalCode);
        $this->assertSame('RU', $pvz->CountryCodeISO);

        $this->assertCount(3, $pvz->OfficeImages);

        $this->assertSame(0, $pvz->WeightLimit->getWeightMin());
        $this->assertSame(30, $pvz->WeightLimit->getWeightMax());
    }

    public function test_postomat()
    {
        $pvz = $this->getSerializer()->deserialize('<Pvz PostalCode="119019">
    <Dimensions depth="580" height="185" width="200"/>
    <Dimensions depth="580" height="445" width="445"/>
    </Pvz>', Pvz::class, 'xml');

        /** @var $pvz Pvz */
        $this->assertSame('119019', $pvz->PostalCode);

        $this->assertCount(2, $pvz->Dimensions);

        $this->assertSame(580.0, $pvz->Dimensions[0]->getDepth());
        $this->assertSame(185.0, $pvz->Dimensions[0]->getHeight());
        $this->assertSame(200.0, $pvz->Dimensions[0]->getWidth());
    }

    public function test_cyrillic_booleans()
    {
        $pvz = $this->getSerializer()->deserialize('<Pvz HaveCashless="есть" AllowedCod="нет" />', Pvz::class, 'xml');

        /** @var $pvz Pvz */
        $this->assertTrue($pvz->HaveCashless);
        $this->assertFalse($pvz->AllowedCod);
    }
}
