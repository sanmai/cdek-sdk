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

final class Location
{
    /**
     * @var array<string, int>
     *
     * @see Region::getCountryCode()
     * @see Location::getCountryCode()
     */
    const COUNTRY_CODE_LOOKUP = [
        'RU' => 1,
    ];

    /**
     * @JMS\XmlAttribute
     * @JMS\Type("string")
     *
     * @var string
     */
    private $cityUuid;

    /**
     * @JMS\XmlAttribute
     * @JMS\Type("string")
     *
     * @var string
     */
    private $cityName;

    /**
     * @JMS\XmlAttribute
     * @JMS\Type("int")
     *
     * @var int
     */
    private $cityCode;

    /**
     * @JMS\XmlAttribute
     * @JMS\Type("string")
     *
     * @var string
     */
    private $region;

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
    private $subRegion;

    /**
     * @JMS\XmlAttribute
     * @JMS\Type("string")
     *
     * @var string
     */
    private $country;

    /**
     * @JMS\XmlAttribute
     * @JMS\Type("string")
     *
     * @var string
     */
    private $countryCode;

    /**
     * @JMS\XmlAttribute
     * @JMS\Type("float")
     *
     * @var float|null
     */
    private $latitude = null;

    /**
     * @JMS\XmlAttribute
     * @JMS\Type("float")
     *
     * @var float|null
     */
    private $longitude = null;

    /**
     * @JMS\XmlAttribute
     * @JMS\Type("string")
     *
     * @var string|null
     */
    private $kladr = null;

    /**
     * @JMS\XmlAttribute
     * @JMS\Type("string")
     *
     * @var string|null
     */
    private $fiasGuid = null;

    /**
     * @JMS\XmlAttribute
     * @JMS\Type("float")
     *
     * @var float
     */
    private $paymentLimit = 0.0;

    /**
     * @JMS\XmlAttribute
     * @JMS\Type("string")
     *
     * @var string|null
     */
    private $timezone;

    public function getName(): string
    {
        return $this->cityName;
    }

    public function getCityName(): string
    {
        return $this->cityName;
    }

    public function getCityCode(): int
    {
        return $this->cityCode;
    }

    public function getCityUuid(): string
    {
        return $this->cityUuid;
    }

    public function getCountry(): string
    {
        return $this->country;
    }

    /**
     * @deprecated СДЭК перешли на использование буквенных кодов стран в начале 2019 года
     * @see Location::getCountryCodeISO()
     */
    public function getCountryCode(): int
    {
        if (\is_numeric($this->countryCode)) {
            return (int) $this->countryCode;
        }

        if (\array_key_exists($this->countryCode, self::COUNTRY_CODE_LOOKUP)) {
            return self::COUNTRY_CODE_LOOKUP[$this->countryCode];
        }

        return 0;
    }

    public function getCountryCodeISO(): string
    {
        if (!\is_numeric($this->countryCode)) {
            return $this->countryCode;
        }

        $isoCountryCode = \array_search($this->countryCode, self::COUNTRY_CODE_LOOKUP);

        if ($isoCountryCode !== false) {
            return $isoCountryCode;
        }

        return '';
    }

    public function getRegion(): string
    {
        return $this->region;
    }

    public function getRegionCode(): int
    {
        return $this->regionCode;
    }

    public function getRegionCodeExt(): int
    {
        return $this->regionCodeExt;
    }

    /**
     * @return string|null
     */
    public function getSubRegion()
    {
        return $this->subRegion;
    }

    public function getPaymentLimit(): float
    {
        return $this->paymentLimit;
    }

    /**
     * @return float|null
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * @return float|null
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * @return string|null
     */
    public function getKladr()
    {
        return $this->kladr;
    }

    /**
     * @return string|null
     */
    public function getFiasGuid()
    {
        return $this->fiasGuid;
    }

    /**
     * @return string|null
     */
    public function getTimezone()
    {
        return $this->timezone;
    }
}
