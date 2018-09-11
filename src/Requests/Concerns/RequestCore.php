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

namespace Appwilio\CdekSDK\Requests\Concerns;

use Appwilio\CdekSDK\Contracts\JsonRequest;
use Appwilio\CdekSDK\Contracts\ParamRequest;
use Appwilio\CdekSDK\Contracts\XmlRequest;

trait RequestCore
{
    /** @phan-suppress PhanUndeclaredConstant */
    public function getAddress(): string
    {
        return static::ADDRESS;
    }

    /** @phan-suppress PhanUndeclaredConstant */
    public function getMethod(): string
    {
        return static::METHOD;
    }

    /** @phan-suppress PhanUndeclaredConstant */
    public function getResponseClassName(): string
    {
        return static::RESPONSE;
    }

    public function getSerializationFormat(): string
    {
        if ($this instanceof XmlRequest) {
            return $this::SERIALIZATION_XML;
        }

        if ($this instanceof JsonRequest) {
            return $this::SERIALIZATION_JSON;
        }

        if ($this instanceof ParamRequest) {
            return $this::SERIALIZATION_XML;
        }

        throw new \BadMethodCallException(sprintf('Class [%s] has an unrecognized serialization format.', __CLASS__));
    }
}
