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

namespace CdekSDK\Responses;

use CdekSDK\Contracts\HasErrorCode;
use CdekSDK\Contracts\Response;
use CdekSDK\Responses\Concerns\WrapsResult;
use CdekSDK\Responses\Types\AdditionalService;
use CdekSDK\Responses\Types\Error;
use CdekSDK\Responses\Types\Result;
use JMS\Serializer\Annotation as JMS;

/**
 * Class CalculationResponse.
 *
 * @method float|null              getPrice()
 * @method int|null                getDeliveryPeriodMin()
 * @method int|null                getDeliveryPeriodMax()
 * @method int|null                getTariffId()
 * @method float|null              getPriceByCurrency()
 * @method float|null              getCashOnDelivery()
 * @method string|null             getCurrency()
 * @method \DateTimeImmutable|null getDeliveryDateMin()
 * @method \DateTimeImmutable|null getDeliveryDateMax()
 * @method AdditionalService[]     getAdditionalServices()
 */
final class CalculationResponse implements Response
{
    use WrapsResult;

    /**
     * @JMS\SerializedName("error")
     * @JMS\Type("array<CdekSDK\Responses\Types\Error>")
     *
     * @var Error[]
     */
    private $errors = [];

    public function hasErrors(): bool
    {
        return !empty($this->errors);
    }

    /**
     * @return HasErrorCode[]|Error[]
     *
     * @deprecated use getMessages()
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    public function getMessages()
    {
        yield from $this->errors;
    }

    public function getResult(): Result
    {
        return $this->result;
    }

    public function jsonSerialize()
    {
        return [];
    }
}
