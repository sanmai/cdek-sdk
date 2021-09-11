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

namespace CdekSDK\Requests;

use CdekSDK\Contracts\ParamRequest;
use CdekSDK\Requests\Concerns\RequestCore;
use CdekSDK\Responses\PvzListResponse;
use function Pipeline\map;

/**
 * @deprecated https://github.com/cdek-it/sdk2.0
 *
 * Class PvzListRequest.
 */
final class PvzListRequest implements ParamRequest
{
    use RequestCore;

    const TYPE_PVZ = 'PVZ';
    const TYPE_ALL = 'ALL';
    const TYPE_POSTOMAT = 'POSTOMAT';

    const LANGUAGE_RUSSIAN = 'rus';
    const LANGUAGE_ENGLISH = 'eng';
    const LANGUAGE_CHINESE = 'zho';

    const METHOD = 'GET';
    const ADDRESS = '/pvzlist/v1/xml';
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
    protected $countryIso;

    /** @var string */
    protected $cityPostCode;

    /** @var bool */
    protected $haveCashless;

    /** @var bool */
    protected $haveCash;

    /** @var bool */
    protected $isDressingRoom;

    /** @var bool */
    protected $allowedCod;

    /** @var int */
    protected $weightMax;

    /** @var string */
    protected $lang;

    /** @var bool */
    protected $takeOnly;

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

    public function setCountryIso(string $countryCode)
    {
        $this->countryIso = $countryCode;

        return $this;
    }

    public function setPostCode(string $code)
    {
        $this->cityPostCode = $code;

        return $this;
    }

    public function setCityPostCode(string $code)
    {
        return $this->setPostCode($code);
    }

    public function setCashless(bool $cashless)
    {
        $this->haveCashless = $cashless;

        return $this;
    }

    public function setCash(bool $cash)
    {
        $this->haveCash = $cash;

        return $this;
    }

    public function setDressingRoom(bool $haveDressingRoom)
    {
        $this->isDressingRoom = $haveDressingRoom;

        return $this;
    }

    public function setCodAllowed(bool $allowedCod)
    {
        $this->allowedCod = $allowedCod;

        return $this;
    }

    public function setMaxWeight(int $weightMax)
    {
        $this->weightMax = $weightMax;

        return $this;
    }

    public function setLanguage(string $lang = self::LANGUAGE_RUSSIAN)
    {
        $this->lang = $lang;

        return $this;
    }

    public function setPickupOnly(bool $takeonly)
    {
        $this->takeOnly = $takeonly;

        return $this;
    }

    /**
     * @psalm-suppress MixedAssignment
     * @psalm-suppress MixedTypeCoercion
     */
    public function getParams(): array
    {
        return \iterator_to_array(map(function () {
            foreach (\get_object_vars($this) as $key => $value) {
                if (\is_null($value)) {
                    continue;
                }

                yield \strtolower($key) => $value;
            }
        }), true);
    }
}
