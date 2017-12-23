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

namespace Appwilio\CdekSDK\Requests;

use Appwilio\CdekSDK\Common\Order;
use Appwilio\CdekSDK\Common\Fillable;
use JMS\Serializer\Annotation as JMS;

/**
 * Class DeliveryRequest
 *
 * @package Appwilio\CdekSDK\Requests\DeliveryRequest
 *
 * @JMS\XmlRoot(name="DeliveryRequest")
 */
class DeliveryRequest implements CdekXmlRequest
{
    use XmlRequestRoot, Fillable;

    protected const METHOD = 'POST';
    protected const ADDRESS = 'http://int.cdek.ru/new_orders.php';

    /**
     * @JMS\XmlAttribute
     * @JMS\SerializedName("Number")
     * @JMS\Type("string")
     *
     * @var string
     */
    protected $Number;

    /**
     * @JMS\XmlList(entry="Order", inline=true)
     * @JMS\Type("array<Appwilio\CdekSDK\Common\Order>")
     *
     * @var array|Order[]
     */
    protected $orders = [];

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

    public function addOrder(Order $order): self
    {
        $this->orders[$order->getNumber()] = $order;

        return $this;
    }

    /**
     * @return Order[]|array
     */
    public function getOrders()
    {
        return $this->orders;
    }

    public function getNumber(): string
    {
        return $this->Number;
    }
}
