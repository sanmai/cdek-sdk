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

namespace Appwilio\CdekSDK\Responses\Types;

use Appwilio\CdekSDK\Contracts\HasMessage;
use JMS\Serializer\Annotation as JMS;

final class PrintError implements HasMessage
{
    /**
     * @JMS\XmlAttribute
     * @JMS\SerializedName("ErrorCode")
     * @JMS\Type("string")
     *
     * @var string
     */
    private $code;

    /**
     * @JMS\XmlAttribute
     * @JMS\SerializedName("Msg")
     * @JMS\Type("string")
     *
     * @var string
     */
    private $text;

    /**
     * @deprecated
     */
    public function getCode(): string
    {
        return $this->getErrorCode();
    }

    /**
     * @deprecated
     */
    public function getText(): string
    {
        return $this->getMessage();
    }

    public function getErrorCode(): string
    {
        return $this->code;
    }

    public function getMessage(): string
    {
        return $this->text;
    }
}
