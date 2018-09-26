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
 * Ожидание курьера в запросе регистрации заказа, ожидание курьера в заявке на вызов курьера.
 *
 * @JMS\XmlRoot(name="Call")
 */
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
     * @JMS\Type("DateTimeImmutable<'H:i'>")
     *
     * @var \DateTimeImmutable
     *
     * @deprecated
     */
    public $TimeBeg;

    /**
     * @JMS\XmlAttribute
     * @JMS\SerializedName("TimeEnd")
     * @JMS\Type("DateTimeImmutable<'H:i'>")
     *
     * @var \DateTimeImmutable
     *
     * @deprecated
     */
    public $TimeEnd;

    /**
     * @JMS\XmlAttribute
     * @JMS\SerializedName("LunchBeg")
     * @JMS\Type("DateTimeImmutable<'H:i'>")
     *
     * @var \DateTimeImmutable
     *
     * @deprecated
     */
    public $LunchBeg;

    /**
     * @JMS\XmlAttribute
     * @JMS\SerializedName("LunchEnd")
     * @JMS\Type("DateTimeImmutable<'H:i'>")
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
     * @JMS\SerializedName("SendCityPostCode")
     * @JMS\Type("string")
     *
     * @var string
     */
    protected $SendCityPostCode;

    /**
     * @JMS\XmlAttribute
     * @JMS\SerializedName("SendCountryCode")
     * @JMS\Type("string")
     *
     * @var string
     */
    protected $SendCountryCode;

    /**
     * @JMS\XmlAttribute
     * @JMS\SerializedName("SendCityName")
     * @JMS\Type("string")
     *
     * @var string
     */
    protected $SendCityName;

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
     * @JMS\SerializedName("SenderName")
     * @JMS\Type("string")
     *
     * @var string
     */
    protected $SenderName;

    /**
     * @JMS\XmlAttribute
     * @JMS\SerializedName("Weight")
     * @JMS\Type("float")
     *
     * @var float
     */
    protected $Weight;

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
     * @JMS\XmlAttribute
     * @JMS\SerializedName("DispatchNumber")
     * @JMS\Type("string")
     *
     * @var string
     */
    protected $DispatchNumber;

    /**
     * @JMS\XmlAttribute
     * @JMS\SerializedName("IgnoreTime")
     * @JMS\Type("bool")
     *
     * @var bool
     */
    protected $IgnoreTime;

    /**
     * @JMS\XmlAttribute
     * @JMS\SerializedName("Number")
     * @JMS\Type("string")
     *
     * @var string
     */
    protected $Number;

    /**
     * @JMS\XmlAttribute
     * @JMS\SerializedName("Msg")
     * @JMS\Type("string")
     *
     * @var string
     */
    protected $Msg;

    /**
     * @JMS\XmlAttribute
     * @JMS\SerializedName("ErrorCode")
     * @JMS\Type("string")
     *
     * @var string
     */
    protected $ErrorCode;

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
     * @JMS\SerializedName("Address")
     * @JMS\Type("CdekSDK\Common\Address")
     *
     * @var Address
     */
    protected $Address;

    /**
     * Устанавливает адрес в целях регистрация заявки на вызов курьера.
     *
     * @param Address $address
     */
    public function setAddress(Address $address): self
    {
        $this->Address = $address;

        return $this;
    }

    public function getNumber(): string
    {
        return (string) $this->Number;
    }

    public function getMessage(): string
    {
        return (string) $this->Msg;
    }

    public function getErrorCode(): string
    {
        return (string) $this->ErrorCode;
    }

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

    /**
     * @JMS\PreSerialize
     */
    private function makeDateTimeImmutable()
    {
        foreach (get_object_vars($this) as $name => $value) {
            if ($value instanceof \DateTime) {
                $this->{$name} = \DateTimeImmutable::createFromMutable($value);
            }
        }
    }
}
