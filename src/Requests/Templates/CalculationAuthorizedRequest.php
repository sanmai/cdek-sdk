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

namespace CdekSDK\Requests\Templates;

use CdekSDK\CdekClient;
use CdekSDK\Common\AdditionalService;
use CdekSDK\Contracts\DateAware;
use CdekSDK\Contracts\JsonRequest;
use CdekSDK\Contracts\ShouldAuthorize;
use CdekSDK\Requests\Concerns\Authorized;
use CdekSDK\Requests\Concerns\RequestCore;

/**
 * Class CalculationAuthorizedRequest.
 */
abstract class CalculationAuthorizedRequest implements JsonRequest, \JsonSerializable, DateAware, ShouldAuthorize
{
    use RequestCore;
    use Authorized;

    const METHOD = 'POST';
    const ADDRESS = '';
    const RESPONSE = '';

    const VERSION = '1.0';

    const SERVICE_INSURANCE = AdditionalService::SERVICE_INSURANCE;  // Страховка
    const SERVICE_DANGEROUS_GOODS = AdditionalService::SERVICE_DANGEROUS_GOODS;  // Опасный груз
    const SERVICE_PICKUP = AdditionalService::SERVICE_PICKUP; // Забор в городе отправителе
    const SERVICE_DELIVERY_TO_DOOR = AdditionalService::SERVICE_DELIVERY_TO_DOOR; // Доставка в городе получателе
    const SERVICE_PACKAGE_1 = AdditionalService::SERVICE_PACKAGE_1; // Упаковка 1
    const SERVICE_PACKAGE_2 = AdditionalService::SERVICE_PACKAGE_2; // Упаковка 2
    const SERVICE_TRY_AT_HOME = AdditionalService::SERVICE_TRY_AT_HOME; // Примерка на дому
    const SERVICE_PERSONAL_DELIVERY = AdditionalService::SERVICE_PERSONAL_DELIVERY; // Доставка лично в руки
    const SERVICE_DOCUMENTS_COPY = AdditionalService::SERVICE_DOCUMENTS_COPY; // Скан документов
    const SERVICE_PARTIAL_DELIVERY = AdditionalService::SERVICE_PARTIAL_DELIVERY; // Частичная доставка
    const SERVICE_CARGO_CHECK = AdditionalService::SERVICE_CARGO_CHECK; // Осмотр вложения

    const MODE_DOOR_DOOR = 1;
    const MODE_DOOR_WAREHOUSE = 2;
    const MODE_WAREHOUSE_DOOR = 3;
    const MODE_WAREHOUSE_WAREHOUSE = 4;

    /**
     * Код города отправителя из базы СДЭК.
     */
    protected $senderCityId;

    /**
     * Индекс города отправителя из базы СДЭКm.
     */
    protected $senderCityPostCode;

    /**
     * Код города получателя из базы СДЭК.
     */
    protected $receiverCityId;

    /**
     * Индекс города получателя из базы СДЭК.
     */
    protected $receiverCityPostCode;

    /**
     * Габаритные характеристики места.
     *
     * @var array[]
     */
    protected $goods = [];

    protected $modeId;

    /**
     * Список передаваемых дополнительных услуг.
     *
     * @var array[]
     */
    protected $services;

    /**
     * Код выбранного тарифа.
     *
     * @var int|null
     */
    protected $tariffId;

    /**
     * Список тарифов.
     *
     * @var array[]
     */
    protected $tariffList = [];

    /**
     * Валюта, в которой необходимо рассчитать стоимость доставки. По умолчанию - RUB.
     *
     * @var string
     */
    protected $currency;

    /**
     * Планируемая дата отправки заказа в формате.
     *
     * @var \DateTimeInterface|null
     */
    protected $dateExecute;

    /**
     * @param int $id
     *
     * @return self
     */
    public function setSenderCityId($id)
    {
        $this->senderCityId = $id;

        return $this;
    }

    /**
     * @param int $id
     *
     * @return self
     */
    public function setReceiverCityId($id)
    {
        $this->receiverCityId = $id;

        return $this;
    }

    /**
     * @param string $code
     *
     * @return self
     */
    public function setSenderCityPostCode($code)
    {
        $this->senderCityPostCode = $code;

        return $this;
    }

    /**
     * @param string $code
     *
     * @return self
     */
    public function setReceiverCityPostCode($code)
    {
        $this->receiverCityPostCode = $code;

        return $this;
    }

    /**
     * @param mixed $id
     *
     * @return self
     */
    public function setTariffId($id)
    {
        $this->tariffList = [];

        $this->tariffId = (int) $id;

        return $this;
    }

    /**
     * @param mixed $id
     * @param mixed $priority
     * @param mixed $modeId
     *
     * @return self
     */
    public function addTariffToList($id, $priority = null, $modeId = null)
    {
        $this->tariffId = null;

        $this->tariffList[] = \array_filter([
            'id'       => $id,
            'priority' => $priority,
            'modeId'   => $modeId,
        ], function ($value) {
            return null !== $value;
        });

        return $this;
    }

    /** @return self */
    public function setModeId($id)
    {
        $this->modeId = $id;

        return $this;
    }

    /**
     * @param array $good
     *
     * @return self
     */
    public function addPackage($good)
    {
        $this->goods[] = $good;

        return $this;
    }

    /**
     * @param int              $serviceId
     * @param int|float|string $param
     *
     * @return self
     */
    public function addAdditionalService($serviceId, $param = null)
    {
        $this->services[] = [
            'id'    => (int) $serviceId,
            'param' => $param,
        ];

        return $this;
    }

    /** @return self */
    public function setCurrency(string $currencyCode)
    {
        $this->currency = $currencyCode;

        return $this;
    }

    /** @return static */
    public function setDateExecute(\DateTimeInterface $date)
    {
        $this->dateExecute = $date;

        return $this;
    }

    public function getRequestDate(): \DateTimeInterface
    {
        return $this->dateExecute ?? new \DateTimeImmutable();
    }

    /** @deprecated */
    public function getBody(): array
    {
        return $this->jsonSerialize();
    }

    /**
     * @phan-suppress PhanDeprecatedProperty
     *
     * @return array
     */
    public function jsonSerialize()
    {
        $result = \array_filter([
            'version'              => self::VERSION,
            'goods'                => $this->goods,
            'modeId'               => $this->modeId,
            'tariffId'             => $this->tariffId,
            'tariffList'           => $this->tariffList,
            'senderCityId'         => $this->senderCityId,
            'senderCityPostCode'   => $this->senderCityPostCode,
            'services'             => $this->services,
            'receiverCityId'       => $this->receiverCityId,
            'receiverCityPostCode' => $this->receiverCityPostCode,
            'currency'             => $this->currency,
            'dateExecute'          => $this->dateExecute instanceof \DateTimeInterface ? $this->dateExecute->format(CdekClient::SECURE_DATE_FORMAT) : null,
        ]);

        if ($this->account === '') {
            return $result;
        }

        return \array_merge($result, [
            'secure'      => $this->secure,
            'authLogin'   => $this->account,
            'dateExecute' => $this->date->format(CdekClient::SECURE_DATE_FORMAT),
        ]);
    }
}
