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
use CdekSDK\Responses\Types\Error;
use CdekSDK\Responses\Types\TariffResult;
use JMS\Serializer\Annotation as JMS;

/**
 * Class CalculationWithTariffListResponse.
 */
final class CalculationWithTariffListResponse implements Response, \IteratorAggregate
{
    /**
     * @JMS\Type("array<CdekSDK\Responses\Types\TariffResult>")
     *
     * @var TariffResult[]
     */
    private $result = [];

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
     * @return Error[]|HasErrorCode[]
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    public function getMessages()
    {
        yield from $this->getErrors();
    }

    /**
     * @return TariffResult[]
     */
    public function getResults()
    {
        return $this->result;
    }

    /**
     * @phan-suppress PhanUnextractableAnnotationSuffix
     *
     * @return \Traversable|TariffResult[]
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->result);
    }

    public function jsonSerialize()
    {
        return [];
    }
}
