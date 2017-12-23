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
use Appwilio\CdekSDK\Common\ChangePeriod;
use JMS\Serializer\Annotation as JMS;

/**
 * Class StatusReport
 *
 * @package Appwilio\CdekSDK\Requests\StatusReport
 *
 * @JMS\XmlRoot(name="StatusReport")
 */
class StatusReportRequest implements CdekXmlRequest
{
    protected const METHOD = 'POST';
    protected const ADDRESS = 'http://int.cdek.ru/status_report_h.php';

    use XmlRequestRoot;

    /**
     * @JMS\XmlAttribute
     * @JMS\SerializedName("ShowHistory")
     * @JMS\Type("int")
     * @var int
     */
    protected $ShowHistory;

    /**
     * @JMS\XmlAttribute
     * @JMS\SerializedName("ShowReturnOrder")
     * @JMS\Type("int")
     * @var int
     */
    protected $ShowReturnOrder;

    /**
     * @JMS\XmlAttribute
     * @JMS\SerializedName("ShowReturnOrder")
     * @JMS\Type("int")
     * @var int
     */
    protected $ShowReturnOrderHistory;

    /**
     * @JMS\XmlList(inline = true, entry = "Order")
     * @JMS\Type("array<Appwilio\CdekSDK\Common\Order>")
     * @var array|Order[]
     */
    protected $orders = [];

    /**
     * @JMS\SerializedName("ChangePeriod")
     * @var ChangePeriod|null
     */
    protected $ChangePeriod;

    public function addOrder(Order $order)
    {
        $this->orders[$order->getDispatchNumber()] = $order;

        return $this;
    }

    public function setChangePeriod(ChangePeriod $period)
    {
        $this->ChangePeriod = $period;

        return $this;
    }

    public function setShowHistory(bool $showHistory = true)
    {
        $this->ShowHistory = (int) $showHistory;

        return $this;
    }

    public function setShowReturnOrder(bool $showReturnOrder = true)
    {
        $this->ShowReturnOrder = (int) $showReturnOrder;

        return $this;
    }

    public function setShowReturnOrderHistory(bool $showReturnOrderHistory = true)
    {
        $this->ShowReturnOrderHistory = (int) $showReturnOrderHistory;

        return $this;
    }

    /**
     * @return Order[]|array
     */
    public function getOrders()
    {
        return $this->orders;
    }
}
