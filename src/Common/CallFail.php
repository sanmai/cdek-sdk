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

/**
 * Class CallGood.
 */
final class CallFail
{
    /**
     * @JMS\XmlAttribute
     * @JMS\SerializedName("Date")
     * @JMS\Type("DateTimeImmutable<'Y-m-d\TH:i:sP'>")
     *
     * @var \DateTimeImmutable
     */
    private $Date;

    /**
     * @JMS\XmlAttribute
     * @JMS\SerializedName("ReasonCode")
     * @JMS\Type("int")
     *
     * @var int
     */
    private $ReasonCode;

    /**
     * @JMS\XmlAttribute
     * @JMS\SerializedName("ReasonDescription")
     * @JMS\Type("string")
     *
     * @var string
     */
    private $ReasonDescription;

    public function getDate(): \DateTimeImmutable
    {
        return $this->Date;
    }

    public function getReasonCode(): int
    {
        return $this->ReasonCode;
    }

    public function getReasonDescription(): string
    {
        return $this->ReasonDescription;
    }
}
