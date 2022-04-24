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

use CdekSDK\Contracts\HasErrorCode;
use JMS\Serializer\Annotation as JMS;

/**
 * @JMS\XmlRoot(name="Order")
 */
final class Order implements HasErrorCode
{
    use Fillable;

    /** Тип Клиента: отправитель. */
    const CLIENT_SIDE_SENDER = 'sender';
    /** Тип Клиента: получатель. */
    const CLIENT_SIDE_RECEIVED = 'receiver';
    /** Тип Клиента: третье лицо. */
    const CLIENT_SIDE_OTHER = 'other';

    public static function withDispatchNumber(string $DispatchNumber): Order
    {
        return Order::create([
            'DispatchNumber' => $DispatchNumber,
        ]);
    }

    public static function withNumberAndDate(string $Number, \DateTimeInterface $Date = null): Order
    {
        return Order::create([
            'Number' => $Number,
            'Date'   => $Date,
        ]);
    }

    /**
     * Код города отправителя из базы СДЭК (см. файл «City_XXX_YYYYMMDD.xls»).
     *
     * @JMS\XmlAttribute
     * @JMS\Type("integer")
     *
     * @var int|null
     */
    protected $SendCityCode;

    /**
     * Код города получателя из базы СДЭК (см. файл «City_XXX_YYYYMMDD.xls»).
     *
     * @JMS\XmlAttribute
     * @JMS\Type("integer")
     *
     * @var int|null
     */
    protected $RecCityCode;

    /**
     * Почтовый индекс города отправителя.
     *
     * @JMS\XmlAttribute
     * @JMS\Type("string")
     *
     * @var string
     */
    protected $SendCityPostCode;

    /**
     * Почтовый индекс города получателя.
     *
     * @JMS\XmlAttribute
     * @JMS\Type("string")
     *
     * @var string
     */
    protected $RecCityPostCode;

    /**
     * Код страны отправителя для идентификации страны в формате ISO_3166-1_alpha-2 (см. “Общероссийский классификатор стран мира”). По умолчанию - RU.
     *
     * @JMS\XmlAttribute
     * @JMS\Type("string")
     *
     * @var string
     */
    protected $SendCountryCode;

    /**
     * Код страны получателя для идентификации страны в формате ISO_3166-1_alpha-2 (см. “Общероссийский классификатор стран мира”). По умолчанию - RU.
     *
     * @JMS\XmlAttribute
     * @JMS\Type("string")
     *
     * @var string
     */
    protected $RecCountryCode;

    /**
     * @JMS\XmlAttribute
     * @JMS\Type("string")
     *
     * @deprecated since 2020-07-14
     *
     * @var string
     */
    protected $ClientSide;

    /**
     * @JMS\XmlAttribute
     * @JMS\Type("DateTimeImmutable<'Y-m-d'>")
     *
     * @var \DateTimeInterface
     */
    protected $Date;

    /**
     * @JMS\XmlAttribute
     * @JMS\Type("DateTimeImmutable<'Y-m-d'>")
     *
     * @var \DateTimeInterface
     */
    protected $DateInvoice;

    /**
     * @JMS\XmlAttribute
     * @JMS\Type("string")
     *
     * @var string
     */
    protected $Number;

    /**
     * @JMS\XmlAttribute
     * @JMS\Type("string")
     *
     * @var string
     */
    protected $DispatchNumber;

    /**
     * @JMS\XmlAttribute
     * @JMS\Type("string")
     *
     * @var string
     */
    protected $RecipientCompany;

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
    protected $RecipientEmail;

    /**
     * @JMS\XmlAttribute
     * @JMS\Type("string")
     *
     * @var string
     */
    protected $RecipientCurrency;

    /**
     * @JMS\XmlAttribute
     * @JMS\Type("string")
     *
     * @var string
     */
    protected $Phone;

    /**
     * @JMS\XmlAttribute
     * @JMS\Type("integer")
     *
     * @var int
     */
    protected $TariffTypeCode;

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
     * @JMS\Type("CdekSDK\Common\DeliveryRecipientCostAdv")
     *
     * @var DeliveryRecipientCostAdv
     */
    protected $DeliveryRecipientCostAdv;

    /**
     * @JMS\XmlAttribute
     * @JMS\Type("string")
     *
     * @var string
     */
    protected $ItemsCurrency;

    /**
     * @JMS\XmlAttribute
     * @JMS\Type("string")
     *
     * @var string
     */
    protected $Comment;

    /**
     * @JMS\Type("CdekSDK\Common\Status")
     *
     * @var Status|null
     */
    protected $Status;

    /**
     * @JMS\Type("CdekSDK\Common\Reason")
     *
     * @var Reason
     */
    protected $Reason;

    /**
     * @JMS\Type("CdekSDK\Common\Reason")
     *
     * @var Reason
     */
    protected $DelayReason;

    /**
     * @JMS\Type("CdekSDK\Common\Address")
     *
     * @var Address
     */
    protected $Address;

    /**
     * @JMS\XmlList(entry="Attempt", inline=true)
     * @JMS\Type("array<CdekSDK\Common\Attempt>")
     *
     * @var Attempt[]
     */
    protected $attempts = [];

    /**
     * @JMS\XmlList(entry="AddedService", inline=true)
     * @JMS\Type("array<CdekSDK\Common\AdditionalService>")
     *
     * @var AdditionalService[]
     */
    protected $addedServices = [];

    /**
     * @JMS\XmlList(entry="AddService", inline=true)
     * @JMS\Type("array<CdekSDK\Common\AdditionalService>")
     *
     * @var AdditionalService[]
     */
    protected $additionalServices = [];

    /**
     * @JMS\SerializedName("Schedule")
     * @JMS\XmlList(entry="Attempt")
     * @JMS\Type("array<CdekSDK\Common\Attempt>")\
     *
     * @var Attempt[]
     */
    protected $scheduleAttempts = [];

    /**
     * @JMS\XmlList(entry="Package", inline=true)
     * @JMS\Type("array<CdekSDK\Common\Package>")
     *
     * @var Package[]
     */
    protected $packages = [];

    /**
     * @JMS\SerializedName("CallCourier")
     * @JMS\XmlList(entry="Call")
     * @JMS\Type("array<CdekSDK\Common\CallCourier>")
     *
     * @var CallCourier[]
     */
    protected $courierCalls = [];

    /**
     * @JMS\XmlAttribute
     * @JMS\Type("float")
     *
     * @var float
     */
    protected $Weight;

    /**
     * @JMS\XmlAttribute
     * @JMS\Type("float")
     *
     * @var float
     */
    protected $DeliverySum;

    /**
     * @JMS\XmlAttribute
     * @JMS\Type("DateTimeImmutable<'Y-m-d\TH:i:sP', '', 'Y-m-d\TH:i:sP', ['Y-m-d H:i:s', 'Y-m-d H:i:sP', 'Y-m-d']>")
     *
     * @var \DateTimeImmutable|null
     */
    protected $DateLastChange;

    /**
     * @JMS\XmlAttribute
     * @JMS\Type("float")
     *
     * @var float
     */
    protected $CashOnDeliv;

    /**
     * @JMS\XmlAttribute
     * @JMS\Type("float")
     *
     * @var float
     */
    protected $CashOnDelivFact;

    /**
     * @JMS\XmlAttribute
     * @JMS\Type("string")
     *
     * @var string
     */
    protected $CashOnDelivType;

    /**
     * @JMS\XmlAttribute
     * @JMS\Type("int")
     *
     * @var int
     */
    protected $DeliveryMode;

    /**
     * @JMS\XmlAttribute
     * @JMS\Type("string")
     *
     * @var string
     */
    protected $PvzCode;

    /**
     * @JMS\XmlAttribute
     * @JMS\Type("string")
     *
     * @var string
     */
    protected $DeliveryVariant;

    /**
     * @JMS\Type("CdekSDK\Common\City")
     *
     * @var City|null
     */
    protected $SendCity;

    /**
     * Наименование города отправителя.
     *
     * @JMS\XmlAttribute
     * @JMS\Type("string")
     *
     * @var string
     */
    protected $SendCityName;

    /**
     * @JMS\Type("CdekSDK\Common\City")
     *
     * @var City|null
     */
    protected $RecCity;

    /**
     * Наименование города получателя.
     *
     * @JMS\XmlAttribute
     * @JMS\Type("string")
     *
     * @var string
     */
    protected $RecCityName;

    /**
     * @JMS\XmlAttribute
     * @JMS\Type("string")
     *
     * @var string
     */
    protected $ErrorCode;

    /**
     * @JMS\XmlAttribute
     * @JMS\Type("string")
     *
     * @var string
     */
    protected $Msg;

    /**
     * @JMS\XmlAttribute
     * @JMS\Type("string")
     *
     * @var string
     *
     * @deprecated
     */
    protected $SellerName;

    /**
     * @JMS\XmlAttribute
     * @JMS\Type("string")
     *
     * @var string
     *
     * @deprecated
     */
    protected $SellerAddress;

    /**
     * @JMS\Type("CdekSDK\Common\Seller")
     *
     * @var Seller
     */
    protected $Seller;

    /**
     * @JMS\XmlAttribute
     * @JMS\Type("string")
     *
     * @var string
     */
    protected $ShipperName;

    /**
     * @JMS\XmlAttribute
     * @JMS\Type("string")
     *
     * @var string
     */
    protected $ShipperAddress;

    /**
     * @JMS\Type("CdekSDK\Common\Sender")
     *
     * @var Sender
     */
    protected $Sender;

    /**
     * @JMS\Type("CdekSDK\Common\Call")
     *
     * @var Call
     */
    protected $Call;

    /**
     * @JMS\Type("CdekSDK\Common\Order")
     *
     * @var Order
     */
    protected $ReturnOrder;

    /**
     * Номер акта приема-передачи.
     *
     * @JMS\XmlAttribute
     * @JMS\Type("string")
     *
     * @var string
     */
    protected $ActNumber;

    /**
     * @JMS\XmlAttribute
     * @JMS\Type("DateTimeImmutable<'Y-m-d\TH:i:sP', '', 'Y-m-d\TH:i:sP', 'Y-m-d'>")
     *
     * @var \DateTimeImmutable|null
     */
    protected $DeliveryDate;

    /**
     * @JMS\XmlAttribute
     * @JMS\Type("int")
     *
     * @var int
     */
    protected $ReturnDispatchNumber;

    /**
     * @JMS\XmlAttribute
     * @JMS\Type("string")
     *
     * @var string
     */
    protected $PassportNumber;

    /**
     * @JMS\Type("CdekSDK\Common\Passport")
     *
     * @var Passport
     */
    protected $Passport;

    /**
     * @JMS\XmlAttribute
     * @JMS\Type("string")
     *
     * @var string
     */
    protected $RecipientINN;

    /**
     * @return Order
     */
    public function callCourier(CallCourier $call)
    {
        $this->courierCalls[] = $call;

        return $this;
    }

    /**
     * @return Order
     */
    public function addDeliveryRecipientCostAdv(DeliveryRecipientCostAdv $surcharge)
    {
        $this->DeliveryRecipientCostAdv = $surcharge;

        return $this;
    }

    /**
     * @return Order
     */
    public function addService(AdditionalService $service)
    {
        $this->additionalServices[] = $service;

        return $this;
    }

    /**
     * @return Order
     */
    public function addScheduleAttempt(Attempt $attempt)
    {
        $this->scheduleAttempts[] = $attempt;

        return $this;
    }

    /**
     * @return Order
     */
    public function setAddress(Address $address)
    {
        $this->Address = $address;

        return $this;
    }

    /**
     * @return Order
     */
    public function setSender(Sender $sender)
    {
        $this->Sender = $sender;

        return $this;
    }

    /**
     * @return Order
     */
    public function setSeller(Seller $seller)
    {
        $this->Seller = $seller;

        return $this;
    }

    /**
     * @return Order
     */
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
     * Возвращает идентификатор заказа для API СДЭК, который состоит либо из DispatchNumber, либо из параметров Number, Date. Запросы по заказам должны включать эти параметры для идентификации заказа.
     */
    public function getId(): string
    {
        if (isset($this->DispatchNumber)) {
            return (string) $this->DispatchNumber;
        }

        if ($this->Date && !empty($this->Number)) {
            return $this->Date->format('Y-m-d').'/'.$this->Number;
        }

        throw new \BadMethodCallException('Order has neither a DispatchNumber nor a Date/Number.');
    }

    public function __toString()
    {
        return $this->getId();
    }

    /**
     * @return int|null
     */
    public function getSendCityCode()
    {
        return $this->SendCity ? $this->SendCity->getCode() : $this->SendCityCode;
    }

    /**
     * @return string|null
     */
    public function getSendCityPostCode()
    {
        return $this->SendCity ? $this->SendCity->getPostCode() : $this->SendCityPostCode;
    }

    /**
     * @return int|null
     */
    public function getRecCityCode()
    {
        return $this->RecCity ? $this->RecCity->getCode() : $this->RecCityCode;
    }

    /**
     * @return string|null
     */
    public function getRecCityPostCode()
    {
        return $this->RecCity ? $this->RecCity->getPostCode() : $this->RecCityPostCode;
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

    /**
     * @phan-suppress PhanDeprecatedProperty
     *
     * @deprecated
     */
    public function getSellerName(): string
    {
        return (string) $this->SellerName;
    }

    /**
     * @return AdditionalService[]
     */
    public function getAdditionalServices()
    {
        return \array_merge($this->additionalServices, $this->addedServices);
    }

    /**
     * @return Attempt[]
     */
    public function getScheduleAttempts()
    {
        return $this->scheduleAttempts;
    }

    /**
     * @return Package[]
     */
    public function getPackages()
    {
        return $this->packages;
    }

    /**
     * @return CallCourier[]
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
     * @return Attempt[]
     */
    public function getAttempts()
    {
        return $this->attempts;
    }

    /** @return self */
    public function addAttempt(Attempt $attempt)
    {
        $this->attempts[] = $attempt;

        return $this;
    }

    public function getWeight(): float
    {
        return (float) $this->Weight;
    }

    public function getDeliverySum(): float
    {
        return (float) $this->DeliverySum;
    }

    /**
     * @return \DateTimeImmutable|null
     */
    public function getDateLastChange()
    {
        return $this->DateLastChange;
    }

    public function getCashOnDeliv(): float
    {
        return (float) $this->CashOnDeliv;
    }

    public function getCashOnDelivFact(): float
    {
        return (float) $this->CashOnDelivFact;
    }

    /**
     * Тип оплаты наложенного платежа. Принимает значения: cash - наличный расчет, cashless - безналичный расчет. Тэг отсутствует, если информация о платеже еще не поступила.
     */
    public function getCashOnDelivType(): string
    {
        return $this->CashOnDelivType;
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

    public function getSenderCity(): ?City
    {
        return $this->SendCity;
    }

    public function getRecipientCity(): ?City
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

    public function getCall(): Call
    {
        return $this->Call;
    }

    public function getReturnOrder(): ?Order
    {
        return $this->ReturnOrder;
    }

    public function getActNumber(): string
    {
        return $this->ActNumber;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getDeliveryDate()
    {
        return $this->DeliveryDate;
    }

    /**
     * Номер возвратной накладной.
     *
     * @todo возвращать строку, как для других номеров отправлений
     */
    public function getReturnDispatchNumber(): int
    {
        return $this->ReturnDispatchNumber;
    }

    /**
     * @JMS\PreSerialize
     */
    private function preSerialize()
    {
        /*
         * Эти поля есть в ответах, но в запросах их использовать недопустимо. Убрать их просил СДЭК.
         * Тем не менее, в примерах эти поля (SendCity и RecCity) использовались, потому, для обратной
         * совместимости, переносим из них значения в действительно используемые поля.
         */
        if ($this->SendCity) {
            $this->SendCityCode = $this->SendCity->getCode();
            $this->SendCityPostCode = $this->SendCity->getPostCode();
            $this->SendCity = null;
        }

        if ($this->RecCity) {
            $this->RecCityCode = $this->RecCity->getCode();
            $this->RecCityPostCode = $this->RecCity->getPostCode();
            $this->RecCity = null;
        }
    }
}
