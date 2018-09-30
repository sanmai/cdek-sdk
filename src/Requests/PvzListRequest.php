<?php

/**
 * This file is part of Cdek SDK package.
 *
 * © Appwilio (http://appwilio.com), greabock (https://github.com/greabock), JhaoDa (https://github.com/jhaoda)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Appwilio\CdekSDK\Requests;

use Appwilio\CdekSDK\Contracts\ParamRequest;
use Appwilio\CdekSDK\Requests\Concerns\RequestCore;

/**
 * Class PvzListRequest
 *
 * @package Appwilio\CdekSDK\Requests
 */
class PvzListRequest implements ParamRequest
{
    use RequestCore;

    public const TYPE_PVZ = 'PVZ';
    public const TYPE_ALL = 'ALL';
    public const TYPE_POSTOMAT = 'POSTOMAT';

    protected const METHOD = 'GET';
    protected const ADDRESS = 'pvzlist.php';

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
            'type'           => $this->type,
            'cityid'         => $this->cityId,
            'regionid'       => $this->regionId,
            'countryid'      => $this->countryId,
            'citypostcode'   => $this->cityPostCode,
            'havecashles'    => $this->cashless,
            'weightmax'      => $this->maxWeight,
            'allowedcod'     => $this->codAllowed,
            'isdressingroom' => $this->dressingRoom,
        ];
    }
}
