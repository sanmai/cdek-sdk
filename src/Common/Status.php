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

/** @phan-suppress PhanDeprecatedInterface */
final class Status implements State
{
    use Fillable;

    const STATUS_DELIVERED = 4;
    const STATUS_NOT_DELIVERED = 5;

    /**
     * @JMS\XmlAttribute
     * @JMS\Type("DateTimeImmutable<'Y-m-d\TH:i:sP'>")
     *
     * @var \DateTimeImmutable
     */
    protected $Date;

    /**
     * @JMS\XmlAttribute
     * @JMS\Type("integer")
     *
     * @var int
     */
    protected $Code;

    /**
     * @JMS\XmlAttribute
     * @JMS\Type("string")
     *
     * @var string
     */
    protected $Description;

    /**
     * @JMS\XmlAttribute
     * @JMS\Type("integer")
     *
     * @var int
     */
    protected $CityCode;

    /**
     * @JMS\XmlAttribute
     * @JMS\Type("string")
     *
     * @var string
     */
    protected $CityName;

    /**
     * @JMS\XmlList(entry="State", inline=true)
     * @JMS\Type("array<CdekSDK\Common\Status>")
     *
     * @var Status[]
     */
    protected $states = [];

    public function getDate(): \DateTimeImmutable
    {
        return $this->Date;
    }

    public function getCode(): int
    {
        return $this->Code;
    }

    public function getDescription(): string
    {
        return $this->Description;
    }

    public function getCityCode(): int
    {
        return $this->CityCode;
    }

    public function getCityName(): string
    {
        return $this->CityName;
    }

    /**
     * @return Status[]
     */
    public function getStates(): array
    {
        return $this->states;
    }

    public function isFinal(): bool
    {
        return $this->getCode() === self::STATUS_DELIVERED || $this->getCode() === self::STATUS_NOT_DELIVERED;
    }
}
