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

namespace Appwilio\CdekSDK\Requests;

use Appwilio\CdekSDK\Common\Fillable;
use Appwilio\CdekSDK\Common\Order;
use Appwilio\CdekSDK\Contracts\ShouldAuthorize;
use Appwilio\CdekSDK\Contracts\XmlRequest;
use Appwilio\CdekSDK\Requests\Concerns\Authorized;
use Appwilio\CdekSDK\Requests\Concerns\OrdersAware;
use Appwilio\CdekSDK\Requests\Concerns\RequestCore;
use Appwilio\CdekSDK\Responses\DeliveryResponse;
use JMS\Serializer\Annotation as JMS;

/**
 * Class DeliveryRequest.
 *
 * @see DeleteRequest
 *
 * @JMS\XmlRoot(name="DeliveryRequest")
 */
class DeliveryRequest implements XmlRequest, ShouldAuthorize
{
    use Authorized, Fillable, OrdersAware, RequestCore;

    const METHOD = 'POST';
    const ADDRESS = '/new_orders.php';
    const RESPONSE = DeliveryResponse::class;

    /**
     * @JMS\XmlAttribute
     * @JMS\SerializedName("Number")
     * @JMS\Type("string")
     *
     * @var string
     */
    protected $number;

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

    public function addOrder(Order $order)
    {
        $this->orders[$order->getNumber()] = $order;

        return $this;
    }

    public function getNumber(): string
    {
        return $this->number;
    }
}
