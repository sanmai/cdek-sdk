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
