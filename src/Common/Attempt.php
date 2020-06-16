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
 * @JMS\XmlRoot(name="Attempt")
 */
final class Attempt
{
    use Fillable;

    /**
     * @JMS\XmlAttribute
     * @JMS\Type("string")
     *
     * @var string
     */
    protected $ID;

    /**
     * @JMS\XmlAttribute
     * @JMS\Type("DateTimeImmutable<'Y-m-d'>")
     *
     * @var \DateTimeImmutable
     */
    protected $Date;

    /**
     * @JMS\XmlAttribute
     * @JMS\Type("string")
     *
     * @var string
     */
    protected $Comment;

    /**
     * @JMS\XmlAttribute
     * @JMS\Type("float")
     *
     * @var float
     */
    protected $DeliveryRecipientCost;

    /**
     * @JMS\XmlAttribute
     * @JMS\Type("string")
     *
     * @var string
     */
    protected $DeliveryRecipientVATRate;

    /**
     * @JMS\XmlAttribute
     * @JMS\Type("float")
     *
     * @var float
     */
    protected $DeliveryRecipientVATSum;

    /**
     * @JMS\XmlAttribute
     * @JMS\Type("DateTimeImmutable<'H:i:s'>")
     *
     * @var \DateTimeImmutable
     */
    protected $TimeBeg;

    /**
     * @JMS\XmlAttribute
     * @JMS\Type("DateTimeImmutable<'H:i:s'>")
     *
     * @var \DateTimeImmutable
     */
    protected $TimeEnd;

    /**
     * @JMS\XmlAttribute
     * @JMS\Type("string")
     *
     * @var string
     */
    protected $RecipientName;

    /**
     * @JMS\XmlAttribute
     * @JMS\Type("string")
     *
     * @var string
     */
    protected $Phone;

    /**
     * @JMS\Type("CdekSDK\Common\Address")
     *
     * @var Address
     */
    protected $Address;

    /**
     * @JMS\XmlList(entry = "Package", inline = true)
     * @JMS\Type("array<CdekSDK\Common\Package>")
     *
     * @var Package[]
     */
    protected $packages = [];

    /**
     * @JMS\XmlAttribute
     * @JMS\Type("int")
     *
     * @var int
     */
    protected $ScheduleCode;

    /**
     * @JMS\XmlAttribute
     * @JMS\Type("string")
     *
     * @var string
     */
    protected $ScheduleDescription;

    /** @return self */
    public function addPackage(Package $package)
    {
        $this->packages[] = $package;

        return $this;
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
