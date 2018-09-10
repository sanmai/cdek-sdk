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
