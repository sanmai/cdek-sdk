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

namespace Appwilio\CdekSDK\Responses;

use Appwilio\CdekSDK\Common\Order;
use Appwilio\CdekSDK\Responses\Types\DeliveryRequest;
use Appwilio\CdekSDK\Responses\Types\Message;
use JMS\Serializer\Annotation as JMS;
use function Pipeline\fromArray;
use function Pipeline\map;

/**
 * Class DeliveryResponse.
 *
 * @see DeleteResponse
 */
final class DeliveryResponse
{
    /**
     * @JMS\XmlList(entry = "DeliveryRequest", inline = true)
     * @JMS\Type("array<Appwilio\CdekSDK\Responses\Types\DeliveryRequest>")
     *
     * @var array|DeliveryRequest[]
     */
    private $requests = [];

    /**
     * @JMS\XmlList(entry = "Order", inline = true)
     * @JMS\Type("array<Appwilio\CdekSDK\Common\Order>")
     *
     * @var array|Order[]
     */
    private $orders = [];

    /**
     * @JMS\Exclude
     *
     * @var \Traversable|Order[]
     */
    private $completeOrders;

    /**
     * @JMS\Exclude
     *
     * @var \Traversable|Message[]
     */
    private $messages;

    /**
     * @return \Traversable|Order[]
     */
    public function getOrders()
    {
        return $this->completeOrders;
    }

    /**
     * @JMS\PostDeserialize
     */
    public function filterOrders()
    {
        $this->completeOrders = fromArray($this->orders)->filter(function (Order $order) {
            return (bool) $order->getDispatchNumber();
        });

        $this->messages = map(function () {
            yield from fromArray($this->orders)->map(function (Order $input) {
                return $input->getMessage();
            })->filter()->map(function ($messageText) {
                yield new Message($messageText);
            });

            yield from fromArray($this->requests)->map(function (DeliveryRequest $input) {
                yield new Message($input->getMessage(), $input->getErrorCode());
            });
        });
    }

    /**
     * @return \Traversable|Message[]
     */
    public function getMessages()
    {
        return $this->messages;
    }
}
