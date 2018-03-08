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

use Appwilio\CdekSDK\Contracts\JsonRequest;
use Appwilio\CdekSDK\Requests\Concerns\RequestCore;

/**
 * Class CalculationRequest
 *
 * @package Appwilio\CdekSDK\Requests
 */
class CalculationRequest implements JsonRequest
{
    use RequestCore;

    public const MODE_DOOR_DOOR = 1;
    public const MODE_DOOR_WAREHOUSE = 2;
    public const MODE_WAREHOUSE_DOOR = 3;
    public const MODE_WAREHOUSE_WAREHOUSE = 4;

    protected const METHOD = 'POST';
    protected const ADDRESS = 'https://api.cdek.ru/calculator/calculate_price_by_json.php';

    protected $senderCityId;
    protected $senderCityPostCode;

    protected $goods;
    protected $modeId;
    protected $tariffId;
    protected $tariffList = [];
    protected $receiverCityId;
    protected $receiverCityPostCode;

    public static function withAuthorization(): CalculationRequest
    {
        return new CalculationAuthorizedRequest();
    }

    public function setSenderCityId($id)
    {
        $this->senderCityId = $id;

        return $this;
    }

    public function setReceiverCityId($id)
    {
        $this->receiverCityId = $id;

        return $this;
    }

    public function setSenderCityPostCode($code)
    {
        $this->senderCityPostCode = $code;

        return $this;
    }

    public function setReceiverCityPostCode($code)
    {
        $this->receiverCityPostCode = $code;

        return $this;
    }

    public function setTariffId($id)
    {
        $this->tariffList = null;

        $this->tariffId = $id;

        return $this;
    }

    public function addTariffToList($id, $priority)
    {
        $this->tariffId = null;

        $this->tariffList[] = compact('id', 'priority');

        return $this;
    }

    public function setModeId($id)
    {
        $this->modeId = $id;

        return $this;
    }

    public function addGood($good)
    {
        $this->goods[] = $good;

        return $this;
    }

    public function getBody(): array
    {
        return array_filter([
            'version'              => '1.0',
            'goods'                => $this->goods,
            'modeId'               => $this->modeId,
            'tariffId'             => $this->tariffId,
            'tariffList'           => $this->tariffList,
            'senderCityId'         => $this->senderCityId,
            'senderCityPostCode'   => $this->senderCityPostCode,
            'receiverCityId'       => $this->receiverCityId,
            'receiverCityPostCode' => $this->receiverCityPostCode,
        ]);
    }
}
