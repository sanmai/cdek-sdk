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

final class Reason
{
    /**
     * @JMS\XmlAttribute
     * @JMS\SerializedName("Date")
     * @JMS\Type("DateTimeImmutable<'Y-m-d\TH:i:sP'>")
     *
     * @var \DateTimeImmutable
     */
    public $Date;

    /**
     * @JMS\XmlAttribute
     * @JMS\SerializedName("Code")
     * @JMS\Type("int")
     *
     * @var int
     */
    public $Code;

    /**
     * @JMS\XmlAttribute
     * @JMS\SerializedName("Description")
     * @JMS\Type("string")
     *
     * @var string
     */
    public $Description;

    /**
     * @JMS\XmlList(entry="State", inline=true)
     * @JMS\Type("array<Appwilio\CdekSDK\Common\State>")
     *
     * @var State[]|array
     */
    public $states = [];
}
