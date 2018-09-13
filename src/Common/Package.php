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

namespace CdekSDK\Common;

use JMS\Serializer\Annotation as JMS;

final class Package
{
    use Fillable;

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
     * @JMS\SerializedName("BarCode")
     * @JMS\Type("string")
     *
     * @var string
     */
    protected $BarCode;

    /**
     * @JMS\XmlAttribute
     * @JMS\SerializedName("Weight")
     * @JMS\Type("int")
     *
     * @var int
     */
    protected $Weight;

    /**
     * @JMS\XmlAttribute
     * @JMS\SerializedName("SizeA")
     * @JMS\Type("int")
     *
     * @var int
     */
    protected $SizeA;

    /**
     * @JMS\XmlAttribute
     * @JMS\SerializedName("SizeB")
     * @JMS\Type("int")
     *
     * @var int
     */
    protected $SizeB;

    /**
     * @JMS\XmlAttribute
     * @JMS\SerializedName("SizeC")
     * @JMS\Type("int")
     *
     * @var int
     */
    protected $SizeC;

    /**
     * @JMS\XmlList(entry="Item", inline=true)
     * @JMS\Type("array<CdekSDK\Common\Item>")
     *
     * @var array|Item[]
     */
    protected $items = [];

    public function addItem(Item $item)
    {
        $this->items[] = $item;

        return $this;
    }

    public function getNumber(): string
    {
        return $this->Number;
    }

    public function getBarCode(): string
    {
        return $this->BarCode;
    }

    public function getWeight(): int
    {
        return $this->Weight;
    }

    public function getSizeA(): int
    {
        return $this->SizeA;
    }

    public function getSizeB(): int
    {
        return $this->SizeB;
    }

    public function getSizeC(): int
    {
        return $this->SizeC;
    }

    public function getVolumeWeight(): float
    {
        return ($this->SizeA * $this->SizeB * $this->SizeC) / 5000;
    }
}
