<?php
/*
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

use CdekSDK\Contracts\HasMessage;
use JMS\Serializer\Annotation as JMS;

/**
 * Class Order.
 */
final class Order implements HasMessage
{
    use Fillable;

    /**
     * @JMS\XmlAttribute
     * @JMS\SerializedName("Date")
     * @JMS\Type("DateTimeImmutable<'Y-m-d'>")
     *
     * @var \DateTimeInterface
     */
    protected $Date;

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
     * @JMS\SerializedName("DispatchNumber")
     * @JMS\Type("string")
     *
     * @var string
     */
    protected $DispatchNumber;

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
     * @JMS\SerializedName("RecipientEmail")
     * @JMS\Type("string")
     *
     * @var string
     */
    protected $RecipientEmail;

    /**
     * @JMS\XmlAttribute
     * @JMS\SerializedName("RecipientCurrency")
     * @JMS\Type("string")
     *
     * @var string
     */
    protected $RecipientCurrency;

    /**
     * @JMS\XmlAttribute
     * @JMS\SerializedName("Phone")
     * @JMS\Type("string")
     *
     * @var string
     */
    protected $Phone;

    /**
     * @JMS\XmlAttribute
     * @JMS\SerializedName("TariffTypeCode")
     * @JMS\Type("integer")
     *
     * @var int
     */
    protected $TariffTypeCode;

    /**
     * @JMS\XmlAttribute
     * @JMS\SerializedName("DeliveryRecipientCost")
     * @JMS\Type("float")
     *
     * @var float
     */
    protected $DeliveryRecipientCost;

    /**
     * @JMS\XmlAttribute
     * @JMS\SerializedName("ItemsCurrency")
     * @JMS\Type("string")
     *
     * @var string
     */
    protected $ItemsCurrency;

    /**
     * @JMS\XmlAttribute
     * @JMS\SerializedName("Comment")
     * @JMS\Type("string")
     *
     * @var string
     */
    protected $Comment;

    /**
     * @JMS\SerializedName("Status")
     * @JMS\Type("CdekSDK\Common\Status")
     *
     * @var Status|null
     */
    protected $Status;

    /**
     * @JMS\SerializedName("Reason")
     * @JMS\Type("CdekSDK\Common\Reason")
     *
     * @var Reason
     */
    protected $Reason;

    /**
     * @JMS\SerializedName("DelayReason")
     * @JMS\Type("CdekSDK\Common\Reason")
     *
     * @var Reason
     */
    protected $DelayReason;

    /**
     * @JMS\SerializedName("Address")
     * @JMS\Type("CdekSDK\Common\Address")
     *
     * @var Address
     */
    protected $Address;

    /**
     * @JMS\XmlList(entry="Attempt", inline=true)
     * @JMS\Type("array<CdekSDK\Common\Attempt>")
     *
     * @var array|Attempt[]
     */
    protected $attempts = [];

    /**
     * @JMS\XmlList(entry="AddedService", inline=true)
     * @JMS\Type("array<CdekSDK\Common\AdditionalService>")
     *
     * @var array|AdditionalService[]
     */
    protected $addedServices = [];

    /**
     * @JMS\XmlList(entry="AddService", inline=true)
     * @JMS\Type("array<CdekSDK\Common\AdditionalService>")
     *
     * @var array|AdditionalService[]
     */
    protected $additionalServices = [];

    /**
     * @JMS\SerializedName("Schedule")
     * @JMS\XmlList(entry="Attempt")
     * @JMS\Type("array<CdekSDK\Common\Attempt>")\
     *
     * @var array|Attempt[]
     */
    protected $scheduleAttempts = [];

    /**
     * @JMS\XmlList(entry="Package", inline=true)
     * @JMS\Type("array<CdekSDK\Common\Package>")
     *
     * @var array|Package[]
     */
    protected $packages = [];

    /**
     * @JMS\SerializedName("CallCourier")
     * @JMS\XmlList(entry="Call")
     * @JMS\Type("array<CdekSDK\Common\CallCourier>")
     *
     * @var array|CallCourier[]
     */
    protected $courierCalls = [];

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
     * @JMS\SerializedName("DeliverySum")
     * @JMS\Type("float")
     *
     * @var float
     */
    protected $DeliverySum;

    /**
     * @JMS\XmlAttribute
     * @JMS\SerializedName("DateLastChange")
     * @JMS\Type("DateTimeImmutable<'Y-m-d\TH:i:sP'>")
     *
     * @var \DateTimeImmutable
     */
    protected $DateLastChange;

    /**
     * @JMS\XmlAttribute
     * @JMS\SerializedName("CashOnDeliv")
     * @JMS\Type("float")
     *
     * @var float
     */
    protected $CashOnDeliv;

    /**
     * @JMS\XmlAttribute
     * @JMS\SerializedName("CashOnDelivFac")
     * @JMS\Type("float")
     *
     * @var float
     */
    protected $CashOnDelivFac;

    /**
     * @JMS\XmlAttribute
     * @JMS\SerializedName("DeliveryMode")
     * @JMS\Type("int")
     *
     * @var int
     */
    protected $DeliveryMode;

    /**
     * @JMS\XmlAttribute
     * @JMS\SerializedName("PvzCode")
     * @JMS\Type("string")
     *
     * @var string
     */
    protected $PvzCode;

    /**
     * @JMS\XmlAttribute
     * @JMS\SerializedName("DeliveryVariant")
     * @JMS\Type("string")
     *
     * @var string
     */
    protected $DeliveryVariant;

    /**
     * @JMS\SerializedName("SendCity")
     * @JMS\Type("CdekSDK\Common\City")
     *
     * @var City
     */
    protected $SendCity;

    /**
     * @JMS\XmlAttribute
     * @JMS\SerializedName("SendCityName")
     * @JMS\Type("string")
     *
     * @var string
     */
    protected $SendCityName;

    /**
     * @JMS\SerializedName("RecCity")
     * @JMS\Type("CdekSDK\Common\City")
     *
     * @var City
     */
    protected $RecCity;

    /**
     * @JMS\XmlAttribute
     * @JMS\SerializedName("RecCityName")
     * @JMS\Type("string")
     *
     * @var string
     */
    protected $RecCityName;

    /**
     * @JMS\XmlAttribute
     * @JMS\SerializedName("ErrorCode")
     * @JMS\Type("string")
     *
     * @var string
     */
    protected $ErrorCode;

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
     * @JMS\SerializedName("SellerName")
     * @JMS\Type("string")
     *
     * @var string
     */
    protected $SellerName;

    /**
     * @JMS\XmlAttribute
     * @JMS\SerializedName("ShipperAddress")
     * @JMS\Type("string")
     *
     * @var string
     */
    protected $SellerAddress;

    /**
     * @JMS\XmlAttribute
     * @JMS\SerializedName("ShipperName")
     * @JMS\Type("string")
     *
     * @var string
     */
    protected $ShipperName;

    /**
     * @JMS\XmlAttribute
     * @JMS\SerializedName("SellerAddress")
     * @JMS\Type("string")
     *
     * @var string
     */
    protected $ShipperAddress;

    /**
     * @JMS\SerializedName("Call")
     * @JMS\Type("CdekSDK\Common\Call")
     *
     * @var Call
     */
    public $Call;

    /**
     * @JMS\SerializedName("ReturnOrder")
     * @JMS\Type("CdekSDK\Common\Order")
     *
     * @var Order
     */
    public $ReturnOrder;

    /**
     * @JMS\XmlAttribute
     * @JMS\SerializedName("ActNumber")
     * @JMS\Type("int")
     *
     * @var int
     */
    public $ActNumber;

    /**
     * @JMS\XmlAttribute
     * @JMS\SerializedName("DeliveryDate")
     * @JMS\Type("DateTimeImmutable<'Y-m-d\TH:i:sP'>")
     *
     * @var \DateTimeImmutable|null
     */
    public $DeliveryDate;

    /**
     * @JMS\XmlAttribute
     * @JMS\SerializedName("ReturnDispatchNumber")
     * @JMS\Type("int")
     *
     * @var int
     */
    public $ReturnDispatchNumber;

    public function callCourier(CallCourier $call)
    {
        $this->courierCalls[] = $call;
    }

    public function addService(AdditionalService $service)
    {
        $this->additionalServices[] = $service;
    }

    public function addScheduleAttempt(Attempt $attempt)
    {
        $this->scheduleAttempts[] = $attempt;
    }

    public function setAddress(Address $address)
    {
        $this->Address = $address;

        return $this;
    }

    public function addPackage(Package $package)
    {
        $this->packages[] = $package;

        return $this;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getDate()
    {
        return $this->Date;
    }

    public function getNumber(): string
    {
        return (string) $this->Number;
    }

    public function getDispatchNumber(): string
    {
        return (string) $this->DispatchNumber;
    }

    /**
     * @JMS\VirtualProperty
     * @JMS\XmlAttribute
     * @JMS\SerializedName("SendCityCode")
     * @JMS\Type("integer")
     *
     * @return int|null
     */
    public function getSendCityCode()
    {
        return $this->SendCity ? $this->SendCity->getCode() : null;
    }

    /**
     * @JMS\VirtualProperty
     * @JMS\XmlAttribute
     * @JMS\SerializedName("SendCityPostCode")
     * @JMS\Type("string")
     *
     * @return null|string
     */
    public function getSendCityPostCode()
    {
        return $this->SendCity ? $this->SendCity->getPostCode() : null;
    }

    /**
     * @JMS\VirtualProperty
     * @JMS\XmlAttribute
     * @JMS\SerializedName("RecCityCode")
     * @JMS\Type("integer")
     *
     * @return int|null
     */
    public function getRecCityCode()
    {
        return $this->RecCity ? $this->RecCity->getCode() : null;
    }

    /**
     * @JMS\VirtualProperty
     * @JMS\XmlAttribute
     * @JMS\SerializedName("RecCityPostCode")
     * @JMS\Type("string")
     *
     * @return null|string
     */
    public function getRecCityPostCode()
    {
        return $this->RecCity ? $this->RecCity->getPostCode() : null;
    }

    public function getRecipientName(): string
    {
        return (string) $this->RecipientName;
    }

    public function getDeliveryRecipientCost(): float
    {
        return (float) $this->DeliveryRecipientCost;
    }

    public function getTariffTypeCode(): int
    {
        return (int) $this->TariffTypeCode;
    }

    public function getPhone(): string
    {
        return (string) $this->Phone;
    }

    public function getRecipientEmail(): string
    {
        return (string) $this->RecipientEmail;
    }

    public function getRecipientCurrency(): string
    {
        return (string) $this->RecipientCurrency;
    }

    public function getItemsCurrency(): string
    {
        return (string) $this->ItemsCurrency;
    }

    public function getComment(): string
    {
        return (string) $this->Comment;
    }

    public function getSellerName(): string
    {
        return (string) $this->SellerName;
    }

    public function getAddress(): Address
    {
        return $this->Address;
    }

    /**
     * @return AdditionalService[]|array
     */
    public function getAdditionalServices()
    {
        return array_merge($this->additionalServices, $this->addedServices);
    }

    /**
     * @return Attempt[]|array
     */
    public function getScheduleAttempts()
    {
        return $this->scheduleAttempts;
    }

    /**
     * @return Package[]|array
     */
    public function getPackages()
    {
        return $this->packages;
    }

    /**
     * @return CallCourier[]|array
     */
    public function getCourierCalls()
    {
        return $this->courierCalls;
    }

    /**
     * @return Status|null
     */
    public function getStatus()
    {
        return $this->Status;
    }

    public function getReason(): Reason
    {
        return $this->Reason;
    }

    public function getDelayReason(): Reason
    {
        return $this->DelayReason;
    }

    /**
     * @return Attempt[]|array
     */
    public function getAttempts()
    {
        return $this->attempts;
    }

    public function getWeight(): float
    {
        return (float) $this->Weight;
    }

    public function getDeliverySum(): float
    {
        return (float) $this->DeliverySum;
    }

    public function getDateLastChange(): \DateTimeImmutable
    {
        return $this->DateLastChange;
    }

    public function getCashOnDeliv(): float
    {
        return (float) $this->CashOnDeliv;
    }

    public function getCashOnDelivFac(): float
    {
        return (float) $this->CashOnDelivFac;
    }

    public function getDeliveryMode(): int
    {
        return (int) $this->DeliveryMode;
    }

    public function getPvzCode(): string
    {
        return (string) $this->PvzCode;
    }

    public function getDeliveryVariant(): string
    {
        return (string) $this->DeliveryVariant;
    }

    public function getSenderCity(): City
    {
        return $this->SendCity;
    }

    public function getRecipientCity(): City
    {
        return $this->RecCity;
    }

    public function getErrorCode(): string
    {
        return (string) $this->ErrorCode;
    }

    public function getMessage(): string
    {
        return (string) $this->Msg;
    }
}
