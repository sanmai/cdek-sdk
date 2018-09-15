<?php
/*
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

namespace CdekSDK\Responses\Types;

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
