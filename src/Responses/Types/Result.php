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

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function getDeliveryPeriodMin(): ?int
    {
        return $this->deliveryPeriodMin;
    }

    public function getDeliveryPeriodMax(): ?int
    {
        return $this->deliveryPeriodMax;
    }

    public function getTariffId(): ?int
    {
        return $this->tariffId;
    }

    public function getPriceByCurrency(): ?float
    {
        return $this->priceByCurrency;
    }

    public function getCurrency(): ?string
    {
        return $this->currency;
    }

    public function getDeliveryDateMin(): ?\DateTimeImmutable
    {
        return $this->deliveryDateMin;
    }

    public function getDeliveryDateMax(): ?\DateTimeImmutable
    {
        return $this->deliveryDateMax;
    }
}
