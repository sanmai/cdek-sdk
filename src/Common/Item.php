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
 * Вложение (товар).
 *
 * Пример данных:
 *
 * <Item WareKey="Ботинки60, размер 40" Comment="Кроссовки мужские" Cost="832" Payment="832" VATRate="VAT18" VATSum="126.92" Weight="0.560" Amount="1" DelivAmount="0" />
 * <Item WareKey="25000358171" CostEx="50" Cost="1500" Weight="2500" WeightBrutto="2600" Amount="1" CommentEx="Waistcoat with the collar" Comment="Жилет с воротником"/>
 */
final class Item
{
    use Fillable;

    /**
     * Идентификатор/артикул товара/вложения (Уникален в пределах упаковки Package).
     *
     * @JMS\XmlAttribute
     * @JMS\Type("string")
     *
     * @var string
     */
    protected $WareKey;

    /**
     * Маркировка товара/вложения.
     *
     * @JMS\XmlAttribute
     * @JMS\Type("string")
     *
     * @var string
     */
    protected $Marking;

    /**
     * Value of each item in the request's currency. For foreign orders only.
     *
     * @JMS\XmlAttribute
     * @JMS\Type("float")
     *
     * @var float
     */
    protected $CostEx;

    /**
     * Объявленная стоимость товара (за единицу товара в указанной валюте, значение >=0). С данного значения рассчитывается страховка.
     *
     * @JMS\XmlAttribute
     * @JMS\Type("float")
     *
     * @var float
     */
    protected $Cost;

    /**
     * The payment for each item in the request's currency. For foreign orders only.
     *
     * @JMS\XmlAttribute
     * @JMS\Type("float")
     *
     * @var float
     */
    protected $PaymentEx;

    /**
     * Оплата за товар при получении (за единицу товара в указанной валюте, значение >=0) — наложенный платеж, в случае предоплаты значение = 0.
     *
     * @JMS\XmlAttribute
     * @JMS\Type("float")
     *
     * @var float
     */
    protected $Payment;

    /**
     * Ставка НДС включеная в стоимость (payment) товара.
     *
     * @JMS\XmlAttribute
     * @JMS\Type("string")
     *
     * @var string
     */
    protected $PaymentVATRate;

    /**
     * Сумма НДС, включенного в стоимость (payment) товара.
     *
     * @JMS\XmlAttribute
     * @JMS\Type("float")
     *
     * @var float
     */
    protected $PaymentVATSum;

    /**
     * Ставка НДС включенная в стоимость товара.
     *
     * @JMS\XmlAttribute
     * @JMS\Type("string")
     *
     * @var string
     */
    protected $VATRate;

    /**
     * Сумма НДС, включенного в стоимость товара.
     *
     * @JMS\XmlAttribute
     * @JMS\Type("float")
     *
     * @var float
     */
    protected $VATSum;

    /**
     * Вес (за единицу товара, в граммах).
     *
     * @JMS\XmlAttribute
     * @JMS\Type("float")
     *
     * @var float
     */
    protected $Weight;

    /**
     * Вес брутто (за единицу товара, в граммах).
     *
     * @JMS\XmlAttribute
     * @JMS\Type("float")
     *
     * @var float
     */
    protected $WeightBrutto;

    /**
     * Количество единиц одноименного товара (в штуках).
     *
     * @JMS\XmlAttribute
     * @JMS\Type("int")
     *
     * @var int
     */
    protected $Amount;

    /**
     * Description of the item in Russian.
     *
     * @JMS\XmlAttribute
     * @JMS\Type("string")
     *
     * @var string
     */
    protected $Comment;

    /**
     * Description of the item in English.
     *
     * @JMS\XmlAttribute
     * @JMS\Type("string")
     *
     * @var string
     */
    protected $CommentEx;

    /**
     * Ссылка на сайт интернет-магазина с описанием товара.
     *
     * @JMS\XmlAttribute
     * @JMS\Type("string")
     *
     * @var string
     */
    protected $Link;

    /**
     * @JMS\XmlAttribute
     * @JMS\Type("float")
     *
     * @var float
     */
    protected $DelivAmount;

    public function getWareKey(): string
    {
        return $this->WareKey;
    }

    public function getMarking(): string
    {
        return $this->Marking;
    }

    public function getCost(): float
    {
        return $this->Cost;
    }

    public function getPayment(): float
    {
        return $this->Payment;
    }

    public function getVATRate(): string
    {
        return $this->VATRate;
    }

    public function getVATSum(): float
    {
        return $this->VATSum;
    }

    public function getWeight(): float
    {
        return $this->Weight;
    }

    public function getAmount(): int
    {
        return $this->Amount;
    }

    public function getComment(): string
    {
        return $this->Comment;
    }

    public function getDelivAmount(): float
    {
        return $this->DelivAmount;
    }
}
