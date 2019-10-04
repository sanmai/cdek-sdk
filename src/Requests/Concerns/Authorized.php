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

use CdekSDK\Contracts\ShouldAuthorize;
use JMS\Serializer\Annotation as JMS;

trait Authorized
{
    /**
     * Дата и время в формате ISO 8601:2004: YYYY-MM-DDThh:mm:ss+hh:mm.
     *
     * @see \CdekSDK\CdekClient::getSecure();
     *
     * @JMS\XmlAttribute
     * @JMS\SerializedName("Date")
     * @JMS\Type("DateTimeImmutable<'Y-m-d\TH:i:sP'>")
     *
     * @var \DateTimeInterface
     *
     * @deprecated will be made private/protected
     */
    public $date;

    /**
     * @JMS\XmlAttribute
     * @JMS\SerializedName("Account")
     * @JMS\Type("string")
     *
     * @var string
     *
     * @deprecated will be made private/protected
     */
    public $account;

    /**
     * @JMS\XmlAttribute
     * @JMS\SerializedName("Secure")
     * @JMS\Type("string")
     *
     * @var string
     *
     * @deprecated will be made private/protected
     */
    public $secure;

    /**
     * @phan-suppress PhanTypeMismatchReturn
     * @phan-suppress PhanDeprecatedProperty
     */
    final public function date(\DateTimeInterface $date): ShouldAuthorize
    {
        $this->date = $date;

        /** @var ShouldAuthorize $this */
        return $this;
    }

    /**
     * @phan-suppress PhanTypeMismatchReturn
     * @phan-suppress PhanDeprecatedProperty
     */
    final public function credentials(string $account, string $secure): ShouldAuthorize
    {
        $this->account = $account;
        $this->secure = $secure;

        /** @var ShouldAuthorize $this */
        return $this;
    }
}
