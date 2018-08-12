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

class ChangePeriod
{
    /**
     * @JMS\XmlAttribute
     * @JMS\SerializedName("DateBeg")
     * @JMS\Type("DateTimeImmutable<'Y-m-d'>")
     *
     * @var \DateTimeImmutable
     */
    public $DateBeg;

    /**
     * @JMS\XmlAttribute
     * @JMS\SerializedName("DateEnd")
     * @JMS\Type("DateTimeImmutable<'Y-m-d'>")
     *
     * @var \DateTimeImmutable
     */
    public $DateEnd;

    public function __construct(\DateTimeInterface $start, \DateTimeInterface $end)
    {
        $this->DateBeg = $start;
        $this->DateEnd = $end;
    }

    /**
     * @JMS\XmlAttribute
     * @JMS\SerializedName("DateFirst")
     * @JMS\Type("DateTimeImmutable<'Y-m-d'>")
     * @JMS\VirtualProperty()
     */
    public function getDateFirst()
    {
        return $this->DateBeg;
    }

    /**
     * @JMS\XmlAttribute
     * @JMS\SerializedName("DateLast")
     * @JMS\Type("DateTimeImmutable<'Y-m-d'>")
     * @JMS\VirtualProperty()
     */
    public function getDateLast()
    {
        return $this->DateEnd;
    }
}
