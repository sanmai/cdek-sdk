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

namespace Appwilio\CdekSDK\Responses;

use JMS\Serializer\Annotation as JMS;
use Appwilio\CdekSDK\Responses\CalculateResponse\Error;
use Appwilio\CdekSDK\Responses\CalculateResponse\Result;

class CalculationResponse
{
    /**
     * @JMS\SerializedName("result")
     * @JMS\Type("Appwilio\CdekSDK\Responses\CalculateResponse\Result")
     *
     * @var Result
     */
    protected $result;

    /**
     * @JMS\SerializedName("error")
     * @JMS\Type("array<Appwilio\CdekSDK\Responses\CalculateResponse\Error>")
     *
     * @var array|Error[]
     */
    public $errors = [];

    public function hasErrors(): bool
    {
        return ! empty($this->errors);
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

        $class = static::class;

        throw new \BadMethodCallException("Method [$name] not found in [$class].");
    }
}
