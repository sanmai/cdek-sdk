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
use Appwilio\CdekSDK\Responses\Types\Message;
use Appwilio\CdekSDK\Responses\Types\PrintReceiptsError;
use JMS\Serializer\Annotation as JMS;
use function Pipeline\map;

/**
 * Class PrintReceiptsResponse.
 */
final class PrintReceiptsResponse
{
    /**
     * @JMS\XmlList(entry = "Order", inline = true)
     * @JMS\Type("array<Appwilio\CdekSDK\Responses\Types\PrintReceiptsError>")
     *
     * @var PrintReceiptsError[]
     */
    private $orders = [];

    /**
     * @JMS\XmlList(entry = "OrdersPrint", inline = true)
     * @JMS\Type("array<Appwilio\CdekSDK\Responses\Types\PrintReceiptsError>")
     *
     * @var PrintReceiptsError[]
     */
    private $ordersPrint = [];

    /**
     * @JMS\Exclude
     *
     * @var PrintReceiptsError[]
     */
    private $errors;

    /**
     * @return PrintReceiptsError[]
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
        })->map(function (PrintReceiptsError $order) {
            return new Message($order->getMessage(), $order->getErrorCode());
        });
    }
}
