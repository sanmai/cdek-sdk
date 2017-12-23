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

class CalculationRequest implements CdekJsonRequest
{
    public const MODE_DOOR_DOOR = 1;
    public const MODE_DOOR_WAREHOUSE = 2;
    public const MODE_WAREHOUSE_DOOR = 3;
    public const MODE_WAREHOUSE_WAREHOUSE = 4;

    protected const METHOD = 'POST';
    protected const ADDRESS = 'http://api.cdek.ru/calculator/calculate_price_by_json.php';

    protected $senderCityId;
    protected $senderCityPostCode;

    /** @var \DateTime */
    protected $dateExecute;
    protected $authLogin;
    protected $secure;
    protected $receiverCityId;
    protected $receiverCityPostCode;
    protected $tariffId;
    protected $tariffList = [];
    protected $modeId;
    protected $goods;

    public function date(\DateTimeInterface $date): CdekRequest
    {
        $this->dateExecute = $date;

        return $this;
    }

    public function credentials(string $account, string $secure): CdekRequest
    {
        $this->authLogin = $account;
        $this->secure = $secure;

        return $this;
    }

    public function getAddress(): string
    {
        return static::ADDRESS;
    }

    public function getMethod(): string
    {
        return static::METHOD;
    }

    public function setSenderCityId($id): self
    {
        $this->senderCityId = $id;

        return $this;
    }

    public function setReceiverCityId($id): self
    {
        $this->receiverCityId = $id;

        return $this;
    }

    public function setSenderCityPostCode($code): self
    {
        $this->senderCityPostCode = $code;

        return $this;
    }

    public function setReceiverCityPostCode($code): self
    {
        $this->receiverCityPostCode = $code;

        return $this;
    }

    public function setTariffId($id): self
    {
        $this->tariffList = null;

        $this->tariffId = $id;

        return $this;
    }

    public function addTariffToList($id, $priority): self
    {
        $this->tariffId = null;

        $this->tariffList[] = compact('id', 'priority');

        return $this;
    }

    public function setModeId($id): self
    {
        $this->modeId = $id;

        return $this;
    }

    public function addGood($good)
    {
        $this->goods[] = $good;

        return $this;
    }

    public function getBody()
    {
        return array_filter([
            'dateExecute'          => $this->dateExecute->format('Y-m-d'),
            'authLogin'            => $this->authLogin,
            'secure'               => $this->secure,
            'senderCityId'         => $this->senderCityId,
            'senderCityPostCode'   => $this->senderCityPostCode,
            'receiverCityId'       => $this->receiverCityId,
            'receiverCityPostCode' => $this->receiverCityPostCode,
            'tariffId'             => $this->tariffId,
            'tariffList'           => $this->tariffList,
            'modeId'               => $this->modeId,
            'goods'                => $this->goods,
            'version'              => '1.0',
        ]);
    }
}
