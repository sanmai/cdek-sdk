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

namespace Appwilio\CdekSDK\Common;

use JMS\Serializer\Annotation as JMS;

class Attempt
{
    use Fillable;

    /**
     * @JMS\XmlAttribute
     * @JMS\SerializedName("ID")
     * @JMS\Type("string")
     *
     * @var string
     */
    protected $ID;

    /**
     * @JMS\XmlAttribute
     * @JMS\SerializedName("Date")
     * @JMS\Type("DateTimeImmutable<'Y-m-d'>")
     *
     * @var \DateTimeImmutable
     */
    protected $Date;

    /**
     * @JMS\XmlAttribute
     * @JMS\SerializedName("TimeBeg")
     * @JMS\Type("DateTimeImmutable<'H:i:s'>")
     *
     * @var \DateTimeImmutable
     */
    protected $TimeBeg;

    /**
     * @JMS\XmlAttribute
     * @JMS\SerializedName("TimeEnd")
     * @JMS\Type("DateTimeImmutable<'H:i:s'>")
     *
     * @var \DateTimeImmutable
     */
    protected $TimeEnd;

    /**
     * @JMS\XmlAttribute
     * @JMS\SerializedName("RecipientName")
     * @JMS\Type("string")
     *
     * @var string
     */
    protected $RecipientName;

    /**
     * @JMS\XmlAttribute
     * @JMS\SerializedName("Phone")
     * @JMS\Type("string")
     *
     * @var string
     */
    protected $Phone;

    /**
     * @JMS\SerializedName("Address")
     * @JMS\Type("Appwilio\CdekSDK\Requests\DeliveryRequest\Address")
     *
     * @var Address
     */
    protected $Address;

    /**
     * @JMS\SerializedName('ScheduleCode')
     * @JMS\XmlAttribute
     * @JMS\Type('int')
     *
     * @var int
     */
    public $ScheduleCode;

    /**
     * @JMS\SerializedName('ScheduleDescription')
     * @JMS\XmlAttribute
     * @JMS\Type('string')
     *
     * @var string
     */
    public $ScheduleDescription;

    public function getId(): string
    {
        return $this->ID;
    }

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

    public function getRecipientName(): string
    {
        return $this->RecipientName;
    }

    public function getPhone(): string
    {
        return $this->Phone;
    }

    public function getAddress(): Address
    {
        return $this->Address;
    }

    public function getScheduleCode(): int
    {
        return $this->ScheduleCode;
    }

    public function getScheduleDescription(): string
    {
        return $this->ScheduleDescription;
    }
}
