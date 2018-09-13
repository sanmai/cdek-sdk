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
 *
 */

declare(strict_types=1);

namespace Appwilio\CdekSDK\Requests\Template;

use Appwilio\CdekSDK\Common\Order;
use Appwilio\CdekSDK\Contracts\ShouldAuthorize;
use Appwilio\CdekSDK\Contracts\XmlRequest;
use Appwilio\CdekSDK\Requests\Concerns\OrdersAware;
use Appwilio\CdekSDK\Responses\PrintErrorResponse;
use JMS\Serializer\Annotation as JMS;

abstract class PrintRequest implements XmlRequest, ShouldAuthorize
{
    use OrdersAware;

    const RESPONSE = PrintErrorResponse::class;

    /**
     * @JMS\XmlAttribute
     * @JMS\SerializedName("CopyCount")
     * @JMS\Type("int")
     *
     * @var string
     */
    protected $CopyCount;

    /**
     * @JMS\XmlAttribute
     * @JMS\SerializedName("OrderCount")
     * @JMS\Type("int")
     * @JMS\VirtualProperty()
     */
    public function getOrderCount()
    {
        return \count($this->orders);
    }

    /**
     * Помечен устаревшим так как у заказа в этом методе обязательно должен быть указан
     * номер отправления СДЭК, при том что целевое API позволяет делать распечатки используя
     * номер заказа и дату. А дату текущая реализация указать не позволяет (всегда заменяется
     * текущей). Потому используйте функцию для указаниея трек-номера заказа.
     *
     * @see PrintRequest::addDispatchNumber()
     * @deprecated
     */
    public function addOrder(Order $order)
    {
        if ($order->getDispatchNumber() === '') {
            throw new \BadMethodCallException('Order has no dispatch number.');
        }

        $this->addOrderWithDispatchNumber($order);

        return $this;
    }

    public function addDispatchNumber(string $DispatchNumber)
    {
        $this->addOrderWithDispatchNumber(Order::create([
            'DispatchNumber' => $DispatchNumber,
        ]));

        return $this;
    }

    private function addOrderWithDispatchNumber(Order $order)
    {
        $this->orders[$order->getDispatchNumber()] = $order;

        return $this;
    }
}
