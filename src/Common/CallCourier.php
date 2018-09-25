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

final class CallCourier
{
    use Fillable;

    /**
     * @JMS\XmlAttribute
     * @JMS\SerializedName("Date")
     * @JMS\Type("DateTimeImmutable<'Y-m-d'>")
     *
     * @var \DateTimeImmutable
     *
     * @deprecated
     */
    public $Date;

    /**
     * @JMS\XmlAttribute
     * @JMS\SerializedName("TimeBeg")
     * @JMS\Type("DateTimeImmutable<'H:i:s'>")
     *
     * @var \DateTimeImmutable
     *
     * @deprecated
     */
    public $TimeBeg;

    /**
     * @JMS\XmlAttribute
     * @JMS\SerializedName("TimeEnd")
     * @JMS\Type("DateTimeImmutable<'H:i:s'>")
     *
     * @var \DateTimeImmutable
     *
     * @deprecated
     */
    public $TimeEnd;

    /**
     * @JMS\XmlAttribute
     * @JMS\SerializedName("LunchBeg")
     * @JMS\Type("DateTimeImmutable<'H:i:s'>")
     *
     * @var \DateTimeImmutable
     *
     * @deprecated
     */
    public $LunchBeg;

    /**
     * @JMS\XmlAttribute
     * @JMS\SerializedName("LunchEnd")
     * @JMS\Type("DateTimeImmutable<'H:i:s'>")
     *
     * @var \DateTimeImmutable
     *
     * @deprecated
     */
    public $LunchEnd;

    /**
     * @JMS\XmlAttribute
     * @JMS\SerializedName("SendCityCode")
     * @JMS\Type("integer")
     *
     * @var int
     *
     * @deprecated
     */
    public $SendCityCode;

    /**
     * @JMS\XmlAttribute
     * @JMS\SerializedName("SendPhone")
     * @JMS\Type("string")
     *
     * @var string
     *
     * @deprecated
     */
    public $SendPhone;

    /**
     * @JMS\XmlAttribute
     * @JMS\SerializedName("Comment")
     * @JMS\Type("string")
     *
     * @var string
     *
     * @deprecated
     */
    public $Comment;

    /**
     * @JMS\SerializedName("SendAddress")
     * @JMS\Type("CdekSDK\Common\Address")
     *
     * @var Address
     *
     * @deprecated
     */
    public $SendAddress;

    /**
     * @deprecated
     * @codeCoverageIgnore
     * @phan-suppress PhanDeprecatedProperty
     */
    public function getDate(): \DateTimeImmutable
    {
        return $this->Date;
    }

    /**
     * @deprecated
     * @codeCoverageIgnore
     * @phan-suppress PhanDeprecatedProperty
     */
    public function getTimeBeg(): \DateTimeImmutable
    {
        return $this->TimeBeg;
    }

    /**
     * @deprecated
     * @codeCoverageIgnore
     * @phan-suppress PhanDeprecatedProperty
     */
    public function getTimeEnd(): \DateTimeImmutable
    {
        return $this->TimeEnd;
    }

    /**
     * @deprecated
     * @codeCoverageIgnore
     * @phan-suppress PhanDeprecatedProperty
     */
    public function getLunchBeg(): \DateTimeImmutable
    {
        return $this->LunchBeg;
    }
}
