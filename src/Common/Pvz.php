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
 */

declare(strict_types=1);

namespace Appwilio\CdekSDK\Common;

use JMS\Serializer\Annotation as JMS;

/**
 * Class Pvz.
 */
final class Pvz
{
    const BOOLEAN_FIELDS = ['IsDressingRoom', 'AllowedCod', 'HaveCashless'];

    /**
     * @JMS\XmlAttribute
     * @JMS\SerializedName("Code")
     * @JMS\Type("string")
     *
     * @var string
     */
    public $Code;

    /**
     * @JMS\XmlAttribute
     * @JMS\SerializedName("Name")
     * @JMS\Type("string")
     *
     * @var string
     */
    public $Name;

    /**
     * @JMS\XmlAttribute
     * @JMS\SerializedName("Address")
     * @JMS\Type("string")
     *
     * @var string
     */
    public $Address;

    /**
     * @JMS\XmlAttribute
     * @JMS\SerializedName("AddressComment")
     * @JMS\Type("string")
     *
     * @var string
     */
    public $AddressComment;

    /**
     * @JMS\XmlAttribute
     * @JMS\SerializedName("FullAddress")
     * @JMS\Type("string")
     *
     * @var string
     */
    public $FullAddress;

    /**
     * @JMS\XmlAttribute
     * @JMS\SerializedName("Phone")
     * @JMS\Type("string")
     *
     * @var string
     */
    public $Phone;

    /**
     * @JMS\XmlAttribute
     * @JMS\SerializedName("Email")
     * @JMS\Type("string")
     *
     * @var string
     */
    public $Email;

    /**
     * @JMS\XmlAttribute
     * @JMS\SerializedName("Note")
     * @JMS\Type("string")
     *
     * @var string
     */
    public $Note;

    /**
     * @JMS\XmlAttribute
     * @JMS\SerializedName("coordX")
     * @JMS\Type("float")
     *
     * @var float
     */
    public $coordX;

    /**
     * @JMS\XmlAttribute
     * @JMS\SerializedName("coordY")
     * @JMS\Type("float")
     *
     * @var float
     */
    public $coordY;

    /**
     * @JMS\XmlAttribute
     * @JMS\SerializedName("CountryCode")
     * @JMS\Type("int")
     *
     * @var int
     */
    public $CountryCode;

    /**
     * @JMS\XmlAttribute
     * @JMS\SerializedName("CountryName")
     * @JMS\Type("string")
     *
     * @var string
     */
    public $CountryName;

    /**
     * @JMS\XmlAttribute
     * @JMS\SerializedName("RegionCode")
     * @JMS\Type("int")
     *
     * @var int
     */
    public $RegionCode;

    /**
     * @JMS\XmlAttribute
     * @JMS\SerializedName("RegionName")
     * @JMS\Type("string")
     *
     * @var string
     */
    public $RegionName;

    /**
     * @JMS\XmlAttribute
     * @JMS\SerializedName("CityCode")
     * @JMS\Type("int")
     *
     * @var int
     */
    public $CityCode;

    /**
     * @JMS\XmlAttribute
     * @JMS\SerializedName("City")
     * @JMS\Type("string")
     *
     * @var string
     */
    public $City;

    /**
     * @JMS\XmlAttribute
     * @JMS\SerializedName("IsDressingRoom")
     * @JMS\Type("string")
     *
     * @var bool
     */
    public $IsDressingRoom;

    /**
     * @JMS\XmlAttribute
     * @JMS\SerializedName("HaveCashless")
     * @JMS\Type("string")
     *
     * @var bool
     */
    public $HaveCashless;

    /**
     * @JMS\XmlAttribute
     * @JMS\SerializedName("AllowedCod")
     * @JMS\Type("string")
     *
     * @var bool
     */
    public $AllowedCod;

    /**
     * @JMS\XmlAttribute
     * @JMS\SerializedName("NearestStation")
     * @JMS\Type("string")
     *
     * @var string
     */
    public $NearestStation;

    /**
     * @JMS\XmlAttribute
     * @JMS\SerializedName("MetroStation")
     * @JMS\Type("string")
     *
     * @var string
     */
    public $MetroStation;

    /**
     * @JMS\XmlAttribute
     * @JMS\SerializedName("Site")
     * @JMS\Type("string")
     *
     * @var string
     */
    public $Site;

    /**
     * @JMS\XmlAttribute
     * @JMS\SerializedName("Type")
     * @JMS\Type("string")
     *
     * @var string
     */
    public $Type;

    /**
     * @JMS\XmlAttribute
     * @JMS\SerializedName("WorkTime")
     * @JMS\Type("string")
     *
     * @var string
     */
    public $WorkTime;

    /**
     * @JMS\XmlAttribute
     * @JMS\SerializedName("WeightLimit")
     * @JMS\Type("Appwilio\CdekSDK\Common\WeightLimit")
     *
     * @var WeightLimit
     */
    public $WeightLimit;

    /**
     * @JMS\PostDeserialize
     */
    public function postDeserialize()
    {
        foreach (self::BOOLEAN_FIELDS as $field) {
            $this->{$field} = $this->{$field} === 'есть';
        }
    }
}
