<?php
/*
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

class Status
{
    use Fillable;

    /**
     * @JMS\XmlAttribute
     * @JMS\SerializedName("Date")
     * @JMS\Type("DateTimeImmutable<'Y-m-d\TH:i:sP'>")
     *
     * @var \DateTimeImmutable
     */
    protected $Date;

    /**
     * @JMS\XmlAttribute
     * @JMS\SerializedName("Code")
     * @JMS\Type("integer")
     *
     * @var int
     */
    protected $Code;

    /**
     * @JMS\XmlAttribute
     * @JMS\SerializedName("Description")
     * @JMS\Type("string")
     *
     * @var string
     */
    protected $Description;

    /**
     * @JMS\XmlAttribute
     * @JMS\SerializedName("CityCode")
     * @JMS\Type("integer")
     *
     * @var int
     */
    protected $CityCode;

    /**
     * @JMS\XmlAttribute
     * @JMS\SerializedName("CityName")
     * @JMS\Type("string")
     *
     * @var string
     */
    protected $CityName;

    /**
     * @JMS\XmlList(entry="State", inline=true)
     * @JMS\Type("array<Appwilio\CdekSDK\Common\State>")
     *
     * @var State[]|array
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
     * @return State[]|array
     */
    public function getStates(): array
    {
        return $this->states;
    }
}
