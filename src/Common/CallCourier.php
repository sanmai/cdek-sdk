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

class CallCourier
{
    use Fillable;

    /**
     * @JMS\XmlAttribute
     * @JMS\SerializedName("Date")
     * @JMS\Type("DateTimeImmutable<'Y-m-d'>")
     *
     * @var \DateTimeImmutable
     */
    public $Date;

    /**
     * @JMS\XmlAttribute
     * @JMS\SerializedName("TimeBeg")
     * @JMS\Type("DateTimeImmutable<'H:i:s'>")
     *
     * @var \DateTimeImmutable
     */
    public $TimeBeg;

    /**
     * @JMS\XmlAttribute
     * @JMS\SerializedName("TimeEnd")
     * @JMS\Type("DateTimeImmutable<'H:i:s'>")
     *
     * @var \DateTimeImmutable
     */
    public $TimeEnd;

    /**
     * @JMS\XmlAttribute
     * @JMS\SerializedName("LunchBeg")
     * @JMS\Type("DateTimeImmutable<'H:i:s'>")
     *
     * @var \DateTimeImmutable
     */
    public $LunchBeg;

    /**
     * @JMS\XmlAttribute
     * @JMS\SerializedName("LunchEnd")
     * @JMS\Type("DateTimeImmutable<'H:i:s'>")
     *
     * @var \DateTimeImmutable
     */
    public $LunchEnd;

    /**
     * @JMS\XmlAttribute
     * @JMS\SerializedName("SendCityCode")
     * @JMS\Type("integer")
     *
     * @var integer
     */
    public $SendCityCode;

    /**
     * @JMS\XmlAttribute
     * @JMS\SerializedName("SendPhone")
     * @JMS\Type("string")
     *
     * @var string
     */
    public $SendPhone;

    /**
     * @JMS\XmlAttribute
     * @JMS\SerializedName("Comment")
     * @JMS\Type("string")
     *
     * @var string
     */
    public $Comment;

    /**
     * @JMS\SerializedName("SendAddress")
     * @JMS\Type("Appwilio\CdekSDK\Common\Address")
     *
     * @var Address
     */
    public $SendAddress;

    public function getDate(): \DateTimeImmutable
    {
        return $this->Date;
    }

    public function getTimeBeg(): \DateTimeImmutable
    {
        return $this->TimeBeg;
    }

    public function getTimeEnd(): \DateTimeImmutable
    {
        return $this->TimeEnd;
    }

    public function getLunchBeg(): \DateTimeImmutable
    {
        return $this->LunchBeg;
    }
}
