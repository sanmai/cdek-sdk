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

namespace CdekSDK\Responses\Types;

use JMS\Serializer\Annotation as JMS;

final class Result
{
    /**
     * @JMS\Type("float")
     *
     * @var float|null
     */
    private $price;

    /**
     * @JMS\Type("int")
     *
     * @var int|null
     */
    private $deliveryPeriodMin;

    /**
     * @JMS\Type("int")
     *
     * @var int|null
     */
    private $deliveryPeriodMax;

    /**
     * @JMS\Type("DateTimeImmutable<'Y-m-d'>")
     *
     * @var \DateTimeImmutable|null
     */
    private $deliveryDateMin;

    /**
     * @JMS\Type("DateTimeImmutable<'Y-m-d'>")
     *
     * @var \DateTimeImmutable|null
     */
    private $deliveryDateMax;

    /**
     * @JMS\Type("int")
     *
     * @var int
     */
    private $tariffId;

    /**
     * @JMS\Type("float")
     *
     * @var float|null
     */
    private $priceByCurrency;

    /**
     * @JMS\Type("string")
     *
     * @var string|null
     */
    private $currency;

    /**
     * @JMS\Type("array<CdekSDK\Responses\Types\AdditionalService>")
     *
     * @var AdditionalService[]
     */
    private $services = [];

    /**
     * @return float|null
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @return int|null
     */
    public function getDeliveryPeriodMin()
    {
        return $this->deliveryPeriodMin;
    }

    /**
     * @return int|null
     */
    public function getDeliveryPeriodMax()
    {
        return $this->deliveryPeriodMax;
    }

    /**
     * @return int|null
     */
    public function getTariffId()
    {
        return $this->tariffId;
    }

    /**
     * @return float|null
     */
    public function getPriceByCurrency()
    {
        return $this->priceByCurrency;
    }

    /**
     * @return string|null
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * @return \DateTimeImmutable|null
     */
    public function getDeliveryDateMin()
    {
        return $this->deliveryDateMin;
    }

    /**
     * @return \DateTimeImmutable|null
     */
    public function getDeliveryDateMax()
    {
        return $this->deliveryDateMax;
    }

    /**
     * @return AdditionalService[]
     */
    public function getAdditionalServices()
    {
        return $this->services;
    }
}
