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

use CdekSDK\Requests\CitiesRequest;
use PHPUnit\Framework\TestCase;

/**
 * @covers \CdekSDK\Requests\CitiesRequest
 */
class CitiesRequestTest extends TestCase
{
    public function test_example()
    {
        $request = new CitiesRequest();
        $request->setPage(1)->setRegionCode(10);
        $request = $request->setSize(20);

        $this->assertSame([
            'regionCode' => 10,
            'page'       => 1,
            'size'       => 20,
        ], $request->getParams());
    }

    public function test_with_everything()
    {
        $request = new CitiesRequest();
        $request = $request->setRegionCodeExt(1);
        $request = $request->setRegionCode(2);
        $request = $request->setPage(6);
        $request = $request->setSize(7);

        $this->assertSame([
            'regionCodeExt'  => 1,
            'regionCode'     => 2,
            'page'           => 6,
            'size'           => 7,
        ], $request->getParams());
    }

    public function test_with_new_properties()
    {
        $request = new CitiesRequest();
        $request = $request->setRegionFiasGuid('example');
        $request = $request->setCountryCode('RU');
        $request = $request->setCityName('Новосибирск');
        $request = $request->setCityCode(1);
        $request = $request->setPostcode('111222');

        $this->assertSame([
            'regionFiasGuid' => 'example',
            'countryCode'    => 'RU',
            'cityName'       => 'Новосибирск',
            'cityCode'       => 1,
            'postcode'       => '111222',
        ], $request->getParams());
    }

    public function test_with_language()
    {
        $request = new CitiesRequest();
        $request = $request->setLang('rus');

        $this->assertSame([
            'lang' => 'rus',
        ], $request->getParams());
    }

    public function testFillable()
    {
        $request = new CitiesRequest([
            'regionCodeExt'  => 1,
            'regionCode'     => 2,
            'page'           => 6,
            'size'           => 7,
        ]);

        $this->assertSame([
            'regionCodeExt'  => 1,
            'regionCode'     => 2,
            'page'           => 6,
            'size'           => 7,
        ], $request->getParams());
    }
}
