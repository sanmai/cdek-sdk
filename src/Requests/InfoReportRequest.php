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
 * Class InfoRequest
 * @package Appwilio\CdekSDK\Requests\InfoRequest
 *
 * @JMS\XmlRoot(name="InfoRequest")
 */
class InfoReportRequest implements CdekXmlRequest
{
    protected const METHOD = 'POST';
    protected const ADDRESS = 'http://int.cdek.ru/info_report.php';

    use XmlRequestRoot;

    /**
     * @JMS\SerializedName("ChangePeriod")
     * @JMS\Type("string")
     * @var ChangePeriod|null
     */
    protected $ChangePeriod;

    /**
     * @JMS\XmlList(inline = true, entry = "Order")
     * @JMS\Type("array<Appwilio\CdekSDK\Common\Order>")
     * @var int
     */
    protected $orders = [];

    public function addOrder(Order $order)
    {
        $this->orders[] = $order;

        return $this;
    }

    public function setChangePeriod(ChangePeriod $period)
    {
        $this->ChangePeriod = $period;

        return $this;
    }
}
