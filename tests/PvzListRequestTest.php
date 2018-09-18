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

namespace Tests\CdekSDK;

use CdekSDK\Requests\PvzListRequest;
use PHPUnit\Framework\TestCase;

/**
 * @covers \CdekSDK\Requests\PvzListRequest
 */
class PvzListRequestTest extends TestCase
{
    public function test_can_get_params()
    {
        $request = (new PvzListRequest())
            ->setCityId(1)
            ->setRegionId(2)
            ->setCountryId(3)
            ->setPostCode('123456');

        $this->assertEquals([
            'type' => PvzListRequest::TYPE_PVZ,
            'cityid' => 1,
            'regionid' => 2,
            'countryid' => 3,
            'citypostcode' => '123456',
            'havecashles' => null,
            'weightmax' => null,
            'allowedcod' => null,
            'isdressingroom' => null,
        ], $request->getParams());

        $request = (new PvzListRequest())
            ->setType(PvzListRequest::TYPE_POSTOMAT)
            ->setCashless(true)
            ->setDressingRoom(false)
            ->setCodAllowed(true)
            ->setMaxWeight(100);

        $this->assertEquals([
            'type' => PvzListRequest::TYPE_POSTOMAT,
            'cityid' => null,
            'regionid' => null,
            'countryid' => null,
            'citypostcode' => null,
            'havecashles' => true,
            'weightmax' => 100,
            'allowedcod' => true,
            'isdressingroom' => false,
        ], $request->getParams());
    }
}
