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

namespace CdekSDK\Requests\Concerns;

use CdekSDK\Contracts\JsonRequest;
use CdekSDK\Contracts\ParamRequest;
use CdekSDK\Contracts\XmlRequest;

trait RequestCore
{
    /**
     * @phan-suppress PhanUndeclaredConstantOfClass
     * @psalm-suppress MixedInferredReturnType
     */
    final public function getAddress(): string
    {
        return static::ADDRESS;
    }

    /**
     * @phan-suppress PhanUndeclaredConstantOfClass
     * @psalm-suppress MixedInferredReturnType
     */
    final public function getMethod(): string
    {
        return static::METHOD;
    }

    /**
     * @phan-suppress PhanUndeclaredConstantOfClass
     * @psalm-suppress MixedInferredReturnType
     */
    final public function getResponseClassName(): string
    {
        return static::RESPONSE;
    }

    /**
     * @psalm-suppress MixedInferredReturnType
     */
    final public function getSerializationFormat(): string
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

        throw new \BadMethodCallException(\sprintf('Class [%s] has an unrecognized serialization format.', __CLASS__));
    }
}
