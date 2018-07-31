<?php

/**
 * This file is part of Cdek SDK package.
 *
 * © Appwilio (http://appwilio.com), greabock (https://github.com/greabock), JhaoDa (https://github.com/jhaoda)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Appwilio\CdekSDK\Common;

use JMS\Serializer\Annotation as JMS;

class Package
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
     * @JMS\Type("array<Appwilio\CdekSDK\Common\Item>")
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
