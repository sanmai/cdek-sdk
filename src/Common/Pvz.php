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

/**
 * Class Pvz.
 */
final class Pvz
{
    const BOOLEAN_FIELDS = [
        'IsDressingRoom',
        'AllowedCod',
        'HaveCashless',
        'HaveCash',
        'TakeOnly',
        'IsHandout',
    ];

    /**
     * @JMS\XmlAttribute
     * @JMS\Type("string")
     *
     * @var string
     */
    public $Code;

    /**
     * @JMS\XmlAttribute
     * @JMS\Type("string")
     *
     * @var string
     */
    public $Name;

    /**
     * @JMS\XmlAttribute
     * @JMS\Type("string")
     *
     * @var string
     */
    public $Address;

    /**
     * @JMS\XmlAttribute
     * @JMS\Type("string")
     *
     * @var string
     */
    public $AddressComment;

    /**
     * @JMS\XmlAttribute
     * @JMS\Type("string")
     *
     * @var string
     */
    public $FullAddress;

    /**
     * @JMS\XmlAttribute
     * @JMS\Type("string")
     *
     * @var string
     */
    public $PostalCode;

    /**
     * @JMS\XmlAttribute
     * @JMS\Type("string")
     *
     * @var string
     */
    public $Phone;

    /**
     * @JMS\XmlAttribute
     * @JMS\Type("string")
     *
     * @var string
     */
    public $Email;

    /**
     * @JMS\XmlAttribute
     * @JMS\Type("string")
     *
     * @var string
     */
    public $qqId;

    /**
     * @JMS\XmlAttribute
     * @JMS\Type("string")
     *
     * @var string
     */
    public $Note;

    /**
     * @JMS\XmlAttribute
     * @JMS\Type("float")
     *
     * @var float
     */
    public $coordX;

    /**
     * @JMS\XmlAttribute
     * @JMS\Type("float")
     *
     * @var float
     */
    public $coordY;

    /**
     * @JMS\XmlAttribute
     * @JMS\Type("int")
     *
     * @var int
     */
    public $CountryCode;

    /**
     * @JMS\XmlAttribute
     * @JMS\Type("string")
     * @JMS\SerializedName("countryCodeIso")
     *
     * @var string
     */
    public $CountryCodeISO;

    /**
     * @JMS\XmlAttribute
     * @JMS\Type("string")
     *
     * @var string
     */
    public $CountryName;

    /**
     * @JMS\XmlAttribute
     * @JMS\Type("int")
     *
     * @var int
     */
    public $RegionCode;

    /**
     * @JMS\XmlAttribute
     * @JMS\Type("string")
     *
     * @var string
     */
    public $RegionName;

    /**
     * @JMS\XmlAttribute
     * @JMS\Type("int")
     *
     * @var int
     */
    public $CityCode;

    /**
     * @JMS\XmlAttribute
     * @JMS\Type("string")
     *
     * @var string
     */
    public $City;

    /**
     * @JMS\XmlAttribute
     * @JMS\Type("string")
     *
     * @var string
     */
    public $ownerCode;

    /**
     * @JMS\XmlAttribute
     * @JMS\Type("string")
     *
     * @var bool
     */
    public $IsDressingRoom;

    /**
     * @JMS\XmlAttribute
     * @JMS\Type("string")
     *
     * @var bool
     */
    public $HaveCashless;

    /**
     * @JMS\XmlAttribute
     * @JMS\Type("string")
     *
     * @var bool
     */
    public $HaveCash;

    /**
     * @JMS\XmlAttribute
     * @JMS\Type("string")
     *
     * @var bool
     */
    public $AllowedCod;

    /**
     * @JMS\XmlAttribute
     * @JMS\Type("string")
     *
     * @var bool
     */
    public $TakeOnly;

    /**
     * @JMS\XmlAttribute
     * @JMS\Type("string")
     *
     * @var bool
     */
    public $IsHandout;

    /**
     * @JMS\XmlAttribute
     * @JMS\Type("string")
     *
     * @var string
     */
    public $NearestStation;

    /**
     * @JMS\XmlAttribute
     * @JMS\Type("string")
     *
     * @var string
     */
    public $MetroStation;

    /**
     * @JMS\XmlAttribute
     * @JMS\Type("string")
     *
     * @var string
     */
    public $Site;

    /**
     * @JMS\XmlAttribute
     * @JMS\Type("string")
     *
     * @var string
     */
    public $Type;

    /**
     * @JMS\XmlAttribute
     * @JMS\Type("string")
     *
     * @var string
     */
    public $WorkTime;

    /**
     * @JMS\Type("CdekSDK\Common\WeightLimit")
     *
     * @var WeightLimit
     */
    public $WeightLimit;

    /**
     * @JMS\XmlList(entry="OfficeImage", inline=true)
     * @JMS\Type("array<CdekSDK\Common\OfficeImage>")
     *
     * @var OfficeImage[]
     */
    public $OfficeImages = [];

    /**
     * @JMS\XmlList(entry="WorkTimeY", inline=true)
     * @JMS\Type("array<CdekSDK\Common\WorkTime>")
     *
     * @var WorkTime[]
     */
    public $workTimes;

    /**
     * @JMS\XmlList(entry="PhoneDetail", inline=true)
     * @JMS\Type("array<CdekSDK\Common\PhoneDetail>")
     *
     * @var PhoneDetail[]
     */
    public $phoneDetails;

    /**
     * @JMS\XmlList(entry="Dimensions", inline=true)
     * @JMS\Type("array<CdekSDK\Common\Dimensions>")
     *
     * @var Dimensions[]
     */
    public $Dimensions;

    /**
     * @JMS\PostDeserialize
     */
    private function postDeserialize()
    {
        foreach (self::BOOLEAN_FIELDS as $field) {
            $this->{$field} = $this->{$field} === 'есть' || $this->{$field} === 'true';
        }
    }
}
