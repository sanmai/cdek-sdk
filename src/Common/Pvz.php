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

namespace Appwilio\CdekSDK\Common;

use JMS\Serializer\Annotation as JMS;

/**
 * Class Pvz
 * @package Appwilio\CdekSDK\Common
 */
class Pvz
{
    /**
     * @JMS\XmlAttribute
     * @JMS\SerializedName("Code")
     * @JMS\Type("int")
     *
     * @var int
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
     * @JMS\SerializedName("WorkTime")
     * @JMS\Type("string")
     *
     * @var string
     */
    public $WorkTime;

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
     * @JMS\SerializedName("Phone")
     * @JMS\Type("string")
     *
     * @var string
     */
    public $Phone;

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
     * @JMS\SerializedName("CoordX")
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
     * @JMS\SerializedName("FullAddress")
     * @JMS\Type("string")
     *
     * @var string
     */
    public $FullAddress;

    /**
     * @JMS\XmlAttribute
     * @JMS\SerializedName("IsDressingRoom")
     * @JMS\Type("int")
     *
     * @var int
     */
    public $IsDressingRoom;

    /**
     * @JMS\XmlAttribute
     * @JMS\SerializedName("HaveCashless")
     * @JMS\Type("int")
     *
     * @var int
     */
    public $HaveCashless;

    /**
     * @JMS\XmlAttribute
     * @JMS\SerializedName("AllowedCod")
     * @JMS\Type("int")
     *
     * @var int
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
     * @JMS\SerializedName("WeightLimit")
     * @JMS\Type("Appwilio\CdekSDK\Common\WeightLimit")
     *
     * @var  WeightLimit
     */
    public $WeightLimit;
}
