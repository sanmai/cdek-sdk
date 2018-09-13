<?php
/*
 * This code is licensed under the MIT License.
 *
 * Copyright (c) 2018 appwilio <appwilio.com>
 * Copyright (c) 2018 JhaoDa <jhaoda@gmail.com>
 * Copyright (c) 2018 greabock <greabock17@gmail.com>
 * Copyright (c) 2018 Alexey Kopytko <alexey@kopytko.com>
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

namespace Appwilio\CdekSDK\Requests;

use Appwilio\CdekSDK\Contracts\ParamRequest;
use Appwilio\CdekSDK\Requests\Concerns\RequestCore;
use Appwilio\CdekSDK\Responses\PvzListResponse;

/**
 * Class PvzListRequest.
 */
final class PvzListRequest implements ParamRequest
{
    use RequestCore;

    const TYPE_PVZ = 'PVZ';
    const TYPE_ALL = 'ALL';
    const TYPE_POSTOMAT = 'POSTOMAT';

    const METHOD = 'GET';
    const ADDRESS = '/pvzlist.php';
    const RESPONSE = PvzListResponse::class;

    /** @var string */
    protected $type = self::TYPE_PVZ;

    /** @var int */
    protected $cityId;

    /** @var int */
    protected $regionId;

    /** @var int */
    protected $countryId;

    /** @var string */
    protected $cityPostCode;

    /** @var bool */
    protected $cashless;

    /** @var bool */
    protected $dressingRoom;

    /** @var bool */
    protected $codAllowed;

    /** @var int */
    protected $maxWeight;

    public function setType(string $type)
    {
        $this->type = $type;

        return $this;
    }

    public function setCityId(int $id)
    {
        $this->cityId = $id;

        return $this;
    }

    public function setRegionId(int $regionId)
    {
        $this->regionId = $regionId;

        return $this;
    }

    public function setCountryId(int $countryId)
    {
        $this->countryId = $countryId;

        return $this;
    }

    public function setPostCode(string $code)
    {
        $this->cityPostCode = $code;

        return $this;
    }

    public function setCashless(bool $cashless)
    {
        $this->cashless = $cashless;

        return $this;
    }

    public function setDressingRoom(bool $haveDressingRoom)
    {
        $this->dressingRoom = $haveDressingRoom;

        return $this;
    }

    public function setCodAllowed(bool $codAllowed)
    {
        $this->codAllowed = $codAllowed;

        return $this;
    }

    public function setMaxWeight(int $maxWeight)
    {
        $this->maxWeight = $maxWeight;

        return $this;
    }

    public function getParams(): array
    {
        return [
            'type' => $this->type,
            'cityid' => $this->cityId,
            'regionid' => $this->regionId,
            'countryid' => $this->countryId,
            'citypostcode' => $this->cityPostCode,
            'havecashles' => $this->cashless,
            'weightmax' => $this->maxWeight,
            'allowedcod' => $this->codAllowed,
            'isdressingroom' => $this->dressingRoom,
        ];
    }
}
