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

final class AdditionalService
{
    use Fillable;

    /**
     * Страховка.
     *
     * @var int
     */
    const SERVICE_INSURANCE = 2;

    /**
     * Опасный груз.
     *
     * @var int
     */
    const SERVICE_DANGEROUS_GOODS = 7;

    /**
     * Забор в городе отправителе.
     *
     * @var int
     */
    const SERVICE_PICKUP = 16;

    /**
     * Доставка в городе получателе.
     *
     * @var int
     */
    const SERVICE_DELIVERY_TO_DOOR = 17;

    /**
     * Упаковка 1.
     *
     * @var int
     */
    const SERVICE_PACKAGE_1 = 24;

    /**
     * Упаковка 2.
     *
     * @var int
     */
    const SERVICE_PACKAGE_2 = 25;

    /**
     * Примерка на дому.
     *
     * @var int
     */
    const SERVICE_TRY_AT_HOME = 30;

    /**
     * Доставка лично в руки.
     *
     * @var int
     */
    const SERVICE_PERSONAL_DELIVERY = 31;

    /**
     * Скан документов.
     *
     * @var int
     */
    const SERVICE_DOCUMENTS_COPY = 32;

    /**
     * Частичная доставка.
     *
     * @var int
     */
    const SERVICE_PARTIAL_DELIVERY = 36;

    /**
     * Осмотр вложения.
     *
     * @var int
     */
    const SERVICE_CARGO_CHECK = 37;

    /**
     * @JMS\XmlAttribute
     * @JMS\Type("int")
     *
     * @var int
     */
    protected $ServiceCode;

    /**
     * @JMS\XmlAttribute
     * @JMS\Type("float")
     *
     * @var float
     */
    protected $Sum;

    /**
     * Количество упаковок. Используется и является обязательным только для дополнительной услуги "Упаковка 1".
     *
     * @JMS\XmlAttribute
     * @JMS\Type("int")
     *
     * @var int
     */
    protected $Count;

    /**
     * Объявленная стоимость отправления (заказа). Используется и является обязательным только для дополнительной услуги "Страхование".
     *
     * @JMS\XmlAttribute
     * @JMS\Type("float")
     *
     * @var float
     */
    protected $Cost;

    public function getServiceCode(): int
    {
        return (int) $this->ServiceCode;
    }

    public function getSum(): float
    {
        return (float) $this->Sum;
    }

    /**
     * @param int|array<string, mixed> $codeOrData
     *
     * @see AdditionalService::SERVICE_INSURANCE
     * @see AdditionalService::SERVICE_DANGEROUS_GOODS
     * @see AdditionalService::SERVICE_PICKUP
     * @see AdditionalService::SERVICE_DELIVERY_TO_DOOR
     * @see AdditionalService::SERVICE_PACKAGE_1
     * @see AdditionalService::SERVICE_PACKAGE_2
     * @see AdditionalService::SERVICE_TRY_AT_HOME
     * @see AdditionalService::SERVICE_PERSONAL_DELIVERY
     * @see AdditionalService::SERVICE_DOCUMENTS_COPY
     * @see AdditionalService::SERVICE_PARTIAL_DELIVERY
     * @see AdditionalService::SERVICE_CARGO_CHECK
     */
    public static function create($codeOrData): self
    {
        if (\is_array($codeOrData)) {
            return new static($codeOrData);
        }

        return new static([
            'ServiceCode' => $codeOrData,
        ]);
    }
}
