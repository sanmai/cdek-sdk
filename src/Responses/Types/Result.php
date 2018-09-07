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

class Result
{
    /**
     * @JMS\SerializedName("price")
     * @JMS\Type("float")
     *
     * @var null|float
     */
    protected $price;

    /**
     * @JMS\SerializedName("deliveryPeriodMin")
     * @JMS\Type("int")
     *
     * @var null|int
     */
    protected $deliveryPeriodMin;

    /**
     * @JMS\SerializedName("deliveryPeriodMax")
     * @JMS\Type("int")
     *
     * @var null|int
     */
    protected $deliveryPeriodMax;

    /**
     * @JMS\SerializedName("deliveryDateMin")
     * @JMS\Type("DateTimeImmutable<'Y-m-d'>")
     *
     * @var null|\DateTimeImmutable
     */
    protected $deliveryDateMin;

    /**
     * @JMS\SerializedName("deliveryDateMax")
     * @JMS\Type("DateTimeImmutable<'Y-m-d'>")
     *
     * @var null|\DateTimeImmutable
     */
    protected $deliveryDateMax;

    /**
     * @JMS\SerializedName("tariffId")
     * @JMS\Type("int")
     *
     * @var int
     */
    protected $tariffId;

    /**
     * @JMS\SerializedName("priceByCurrency")
     * @JMS\Type("float")
     *
     * @var null|float
     */
    protected $priceByCurrency;

    /**
     * @JMS\SerializedName("currency")
     * @JMS\Type("string")
     *
     * @var null|string
     */
    protected $currency;

    /**
     * @JMS\SerializedName("services")
     * @JMS\Type("array")
     *
     * @var null|array
     */
    protected $services;

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
