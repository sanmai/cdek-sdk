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

namespace CdekSDK\Responses;

use CdekSDK\Common\Order;
use CdekSDK\Responses\Types\DeleteRequest;
use CdekSDK\Responses\Types\Message;
use JMS\Serializer\Annotation as JMS;
use function Pipeline\fromArray;

/**
 * Class DeleteResponse.
 */
final class DeleteResponse
{
    /**
     * @JMS\XmlList(entry = "DeleteRequest", inline = true)
     * @JMS\Type("array<CdekSDK\Responses\Types\DeleteRequest>")
     *
     * @var array|DeleteRequest[]
     */
    private $requests = [];

    private function getOrdersFromRequests(): \Pipeline\Standard
    {
        return fromArray($this->requests)->map(function (DeleteRequest $request) {
            yield from $request->getOrders();
        });
    }

    /**
     * @return \Traversable|Order[]
     */
    public function getOrders()
    {
        return $this->getOrdersFromRequests();
    }

    /**
     * @return \Traversable|Message[]
     */
    public function getMessages()
    {
        return $this->getOrdersFromRequests()->map(function (Order $order) {
            return new Message($order->getMessage(), $order->getErrorCode());
        });
    }
}
