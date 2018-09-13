<?php
/*
 * This code is licensed under the MIT License.
 *
 * Copyright (c) 2018 appwilio <appwilio.com>
 * Copyright (c) 2018 JhaoDa <jhaoda@gmail.com>
 * Copyright (c) 2018 greabock <greabock17@gmail.com>
 * Copyright (c) 2018 Alexey Kopytko <alexey@kopytko.com>
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
 *
 *
 */

declare(strict_types=1);

namespace Appwilio\CdekSDK\Responses;

use Appwilio\CdekSDK\Responses\Types\Error;
use Appwilio\CdekSDK\Responses\Types\Result;
use JMS\Serializer\Annotation as JMS;

/**
 * Class CalculationResponse.
 */
final class CalculationResponse
{
    /**
     * @JMS\SerializedName("result")
     * @JMS\Type("Appwilio\CdekSDK\Responses\Types\Result")
     *
     * @var Result
     */
    private $result;

    /**
     * @JMS\SerializedName("error")
     * @JMS\Type("array<Appwilio\CdekSDK\Responses\Types\Error>")
     *
     * @var array|Error[]
     */
    public $errors = [];

    public function hasErrors(): bool
    {
        return !empty($this->errors);
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    public function getResult(): Result
    {
        return $this->result;
    }

    public function __call($name, $arguments)
    {
        if ($this->result && method_exists($this->result, $name)) {
            return $this->result->{$name}(...$arguments);
        }

        throw new \BadMethodCallException(sprintf('Method [%s] not found in [%s].', $name, __CLASS__));
    }
}
