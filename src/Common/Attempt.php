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
 */

declare(strict_types=1);

namespace Appwilio\CdekSDK\Common;

use JMS\Serializer\Annotation as JMS;

final class Attempt
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
