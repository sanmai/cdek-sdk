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

namespace Appwilio\CdekSDK\Responses\Types;

use JMS\Serializer\Annotation as JMS;

class Error
{
    /**
     * @JMS\Type("int")
     *
     * @var int
     */
    protected $code;

    /**
     * @JMS\Type("string")
     *
     * @var string
     */
    protected $text;

    public function getCode(): int
    {
        return $this->code;
    }

    public function getText(): string
    {
        return $this->text;
    }
}
