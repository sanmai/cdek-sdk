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

use Appwilio\CdekSDK\Common\DeleteRequest;
use Appwilio\CdekSDK\Common\Order;
use Appwilio\CdekSDK\Responses\Types\Message;
use JMS\Serializer\Annotation as JMS;
use function Pipeline\fromArray;

/**
 * Class DeleteResponse.
 */
final class DeleteResponse
{
    /**
     * @JMS\XmlList(entry = "DeleteRequest", inline = true)
     * @JMS\Type("array<Appwilio\CdekSDK\Common\DeleteRequest>")
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
