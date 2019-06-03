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

namespace CdekSDK\Common;

use JMS\Serializer\Annotation as JMS;

final class Region
{
    /**
     * @JMS\XmlAttribute
     * @JMS\Type("string")
     *
     * @var string
     */
    private $regionUuid;

    /**
     * @JMS\XmlAttribute
     * @JMS\Type("string")
     *
     * @var string
     */
    private $regionName;

    /**
     * @JMS\XmlAttribute
     * @JMS\Type("string")
     *
     * @var string|null
     */
    private $prefix = null;

    /**
     * @JMS\XmlAttribute
     * @JMS\Type("int")
     *
     * @var int
     */
    private $regionCode;

    /**
     * @JMS\XmlAttribute
     * @JMS\Type("int")
     *
     * @var int
     */
    private $regionCodeExt;

    /**
     * @JMS\XmlAttribute
     * @JMS\Type("string")
     *
     * @var string|null
     */
    private $regionFiasGuid = null;

    /**
     * @JMS\XmlAttribute
     * @JMS\Type("string")
     *
     * @var string
     */
    private $countryName;

    /**
     * @JMS\XmlAttribute
     * @JMS\Type("string")
     *
     * @var string
     */
    private $countryCode;

    /**
     * @JMS\XmlAttribute
     * @JMS\Type("int")
     *
     * @var int
     */
    private $countryCodeExt;

    public function getUuid(): string
    {
        return $this->regionUuid;
    }

    public function getName(): string
    {
        return $this->regionName;
    }

    public function getPrefix(): string
    {
        return (string) $this->prefix;
    }

    public function getCode(): int
    {
        return $this->regionCode;
    }

    public function getCodeExt(): int
    {
        return $this->regionCodeExt;
    }

    public function getFiasGuid(): string
    {
        return (string) $this->regionFiasGuid;
    }

    public function getCountryName(): string
    {
        return $this->countryName;
    }

    /**
     * @deprecated СДЭК отказались от числовых кодов на 19.03.2019
     * @see Region::getCountryCodeISO()
     */
    public function getCountryCode(): int
    {
        if (\is_numeric($this->countryCode)) {
            return (int) $this->countryCode;
        }

        if (\array_key_exists($this->countryCode, Location::COUNTRY_CODE_LOOKUP)) {
            return Location::COUNTRY_CODE_LOOKUP[$this->countryCode];
        }

        return 0;
    }

    public function getCountryCodeISO(): string
    {
        return $this->countryCode;
    }

    public function getCountryCodeExt(): int
    {
        return $this->countryCodeExt;
    }
}
