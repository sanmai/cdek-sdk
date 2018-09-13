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

namespace Appwilio\CdekSDK\Responses;

use Appwilio\CdekSDK\Common\Order;
use Appwilio\CdekSDK\Responses\Types\Message;
use Appwilio\CdekSDK\Responses\Types\PrintError;
use JMS\Serializer\Annotation as JMS;
use function Pipeline\map;

/**
 * Class PrintErrorResponse.
 */
final class PrintErrorResponse
{
    /**
     * @JMS\XmlList(entry = "Order", inline = true)
     * @JMS\Type("array<Appwilio\CdekSDK\Responses\Types\PrintError>")
     *
     * @var PrintError[]
     */
    private $orders = [];

    /**
     * @JMS\XmlList(entry = "OrdersPrint", inline = true)
     * @JMS\Type("array<Appwilio\CdekSDK\Responses\Types\PrintError>")
     *
     * @var PrintError[]
     */
    private $ordersPrint = [];

    /**
     * @JMS\Exclude
     *
     * @var PrintError[]
     */
    private $errors;

    /**
     * @return PrintError[]
     *
     * @deprecated
     */
    public function getErrors()
    {
        return array_merge($this->orders, $this->ordersPrint);
    }

    /**
     * @return \Traversable|Message[]
     */
    public function getMessages()
    {
        return map(function () {
            yield from $this->orders;
            yield from $this->ordersPrint;
        })->map(function (PrintError $order) {
            return new Message($order->getMessage(), $order->getErrorCode());
        });
    }
}
