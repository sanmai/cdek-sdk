<?php
/*
 * This file is part of Cdek SDK package.
 *
 * © Appwilio (http://appwilio.com), greabock (https://github.com/greabock), JhaoDa (https://github.com/jhaoda)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Appwilio\CdekSDK\Responses\Types;

use JMS\Serializer\Annotation as JMS;

final class Result
{
    /**
     * @JMS\SerializedName("price")
     * @JMS\Type("float")
     *
     * @var null|float
     */
    private $price;

    /**
     * @JMS\SerializedName("deliveryPeriodMin")
     * @JMS\Type("int")
     *
     * @var null|int
     */
    private $deliveryPeriodMin;

    /**
     * @JMS\SerializedName("deliveryPeriodMax")
     * @JMS\Type("int")
     *
     * @var null|int
     */
    private $deliveryPeriodMax;

    /**
     * @JMS\SerializedName("deliveryDateMin")
     * @JMS\Type("DateTimeImmutable<'Y-m-d'>")
     *
     * @var null|\DateTimeImmutable
     */
    private $deliveryDateMin;

    /**
     * @JMS\SerializedName("deliveryDateMax")
     * @JMS\Type("DateTimeImmutable<'Y-m-d'>")
     *
     * @var null|\DateTimeImmutable
     */
    private $deliveryDateMax;

    /**
     * @JMS\SerializedName("tariffId")
     * @JMS\Type("int")
     *
     * @var int
     */
    private $tariffId;

    /**
     * @JMS\SerializedName("priceByCurrency")
     * @JMS\Type("float")
     *
     * @var null|float
     */
    private $priceByCurrency;

    /**
     * @JMS\SerializedName("currency")
     * @JMS\Type("string")
     *
     * @var null|string
     */
    private $currency;

    /**
     * @JMS\SerializedName("services")
     * @JMS\Type("array")
     *
     * @var null|array
     */
    private $services;

    /**
     * @return null|float
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @return null|int
     */
    public function getDeliveryPeriodMin()
    {
        return $this->deliveryPeriodMin;
    }

    /**
     * @return null|int
     */
    public function getDeliveryPeriodMax()
    {
        return $this->deliveryPeriodMax;
    }

    /**
     * @return null|int
     */
    public function getTariffId()
    {
        return $this->tariffId;
    }

    /**
     * @return null|float
     */
    public function getPriceByCurrency()
    {
        return $this->priceByCurrency;
    }

    /**
     * @return null|string
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * @return null|\DateTimeImmutable
     */
    public function getDeliveryDateMin()
    {
        return $this->deliveryDateMin;
    }

    /**
     * @return null|\DateTimeImmutable
     */
    public function getDeliveryDateMax()
    {
        return $this->deliveryDateMax;
    }

    /**
     * @return array|null
     */
    public function getAdditionalServices()
    {
        return $this->services;
    }
}
