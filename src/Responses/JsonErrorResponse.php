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

namespace CdekSDK\Responses;

use CdekSDK\Contracts\Response;
use CdekSDK\Responses\Types\JsonAlert;
use CdekSDK\Responses\Types\Message;
use JMS\Serializer\Annotation as JMS;

/**
 * Class JsonErrorResponse.
 */
final class JsonErrorResponse implements Response
{
    /**
     * @JMS\Type("int")
     *
     * @var int
     */
    private $timestamp;

    /**
     * @JMS\Type("string")
     *
     * @var string
     */
    private $path;

    /**
     * @JMS\Type("int")
     *
     * @var int
     */
    private $status;

    /**
     * @JMS\Type("string")
     *
     * @var string
     */
    private $error;

    /**
     * @JMS\Type("string")
     *
     * @var string
     */
    private $message;

    /**
     * @JMS\XmlList(entry = "JsonAlert", inline = true)
     *
     * @JMS\Type("array<CdekSDK\Responses\Types\JsonAlert>")
     *
     * @var JsonAlert[]
     */
    private $alerts = [];

    public function hasErrors(): bool
    {
        return true;
    }

    public function getMessages()
    {
        if ($this->alerts) {
            yield from Message::from($this->alerts);
        }

        if ($this->error !== null) {
            yield new Message($this->error, (string) $this->status);
        }
    }

    public function jsonSerialize()
    {
        return [];
    }
}
