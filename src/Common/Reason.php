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
 * Текущий дополнительный статус, текущая причина задержки.
 *
 * Пример данных:
 *
 * <Reason Code="20" Description="Частичная доставка" Date="2018-04-07T12:29:39+00:00"></Reason>
 * <DelayReason Code="" Description="" Date=""><State Date="2018-04-03T16:56:41+00:00" Code="47" Description="Адрес не существует" /></DelayReason>
 */
final class Reason
{
    /**
     * @JMS\XmlAttribute
     * @JMS\SerializedName("Date")
     * @JMS\Type("DateTimeImmutable<'Y-m-d\TH:i:sP'>")
     *
     * @var \DateTimeImmutable|null
     *
     * @deprecated use accessor method
     */
    public $Date;

    /**
     * @JMS\XmlAttribute
     * @JMS\SerializedName("Code")
     * @JMS\Type("int")
     *
     * @var int
     *
     * @deprecated use accessor method
     */
    public $Code;

    /**
     * @JMS\XmlAttribute
     * @JMS\SerializedName("Description")
     * @JMS\Type("string")
     *
     * @var string
     *
     * @deprecated use accessor method
     */
    public $Description;

    /**
     * @JMS\XmlList(entry="State", inline=true)
     * @JMS\Type("array<CdekSDK\Common\State>")
     *
     * @var State[]|array
     *
     * @deprecated use accessor method
     */
    public $states = [];

    /**
     * @phan-suppress PhanDeprecatedProperty
     *
     * @return \DateTimeInterface|null
     */
    public function getDate()
    {
        return $this->Date;
    }

    /**
     * @phan-suppress PhanDeprecatedProperty
     */
    public function getCode(): int
    {
        return (int) $this->Code;
    }

    /**
     * @phan-suppress PhanDeprecatedProperty
     */
    public function getDescription(): string
    {
        return $this->Description;
    }

    /**
     * @phan-suppress PhanDeprecatedProperty
     *
     * @return State[]
     */
    public function getStates()
    {
        return $this->states;
    }
}
