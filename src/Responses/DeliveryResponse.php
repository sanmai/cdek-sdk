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

namespace Appwilio\CdekSDK\Responses;

use Appwilio\CdekSDK\Common\Order;
use Appwilio\CdekSDK\Responses\Types\Message;
use JMS\Serializer\Annotation as JMS;

/**
 * Class DeliveryResponse
 *
 * @package Appwilio\CdekSDK\Responses
 */
class DeliveryResponse
{
    /**
     * @JMS\XmlList(entry = "Order", inline = true)
     * @JMS\Type("array<Appwilio\CdekSDK\Common\Order>")
     *
     * @var array|Order[]
     */
    protected $orders = [];

    /**
     * @JMS\Exclude
     *
     * @var array|Message[]
     */
    protected $messages;

    /**
     * @return Order[]|array
     */
    public function getOrders()
    {
        return $this->orders;
    }

    /**
     * @JMS\PostDeserialize
     */
    public function filterOrders(): void
    {
        $messages = array_filter($this->orders, function (Order $order) {
            return (bool) $order->getMessage();
        });

        foreach ($messages as $message) {
            $this->messages[] = new Message($message->getMessage());
        }

        $this->orders = array_filter($this->orders, function (Order $order) {
            return (bool) $order->getDispatchNumber();
        });
    }

    public function getMessages(): array
    {
        return $this->messages;
    }
}
