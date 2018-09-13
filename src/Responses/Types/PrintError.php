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

namespace CdekSDK\Responses\Types;

use CdekSDK\Contracts\HasMessage;
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
