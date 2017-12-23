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
use JMS\Serializer\Annotation as JMS;

/**
 * Class StatusReport
 *
 * @package Appwilio\CdekSDK\Responses\StatusReport
 */
class StatusReportResponse
{
    /**
     * @JMS\XmlAttribute
     * @JMS\SerializedName("DateFirst")
     * @JMS\Type("DateTimeImmutable<'Y-m-d\TH:i:sP'>")
     *
     * @var \DateTimeImmutable
     */
    protected $DateFirst;

    /**
     * @JMS\XmlAttribute
     * @JMS\SerializedName("DateLast")
     * @JMS\Type("DateTimeImmutable<'Y-m-d\TH:i:sP'>")
     *
     * @var \DateTimeImmutable
     */
    protected $DateLast;

    /**
     * @JMS\XmlList(inline = true, entry = "Order")
     * @JMS\Type("array<Appwilio\CdekSDK\Common\Order>")
     *
     * @var Order[]|array
     */
    protected $orders = [];

    public function getDateFirst(): \DateTimeImmutable
    {
        return $this->DateFirst;
    }

    public function getDateLast(): \DateTimeImmutable
    {
        return $this->DateLast;
    }

    /**
     * @return Order[]|array
     */
    public function getOrders()
    {
        return $this->orders;
    }
}
