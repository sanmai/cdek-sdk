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
use Appwilio\CdekSDK\Contracts\XmlRequest;
use Appwilio\CdekSDK\Contracts\ShouldAuthorize;
use Appwilio\CdekSDK\Requests\Concerns\Authorized;
use Appwilio\CdekSDK\Requests\Concerns\OrdersAware;
use Appwilio\CdekSDK\Requests\Concerns\RequestCore;
use JMS\Serializer\Annotation as JMS;

/**
 * Class InfoReportRequest
 *
 * @JMS\XmlRoot(name="InfoRequest")
 *
 * @package Appwilio\CdekSDK\Requests
 */
class InfoReportRequest implements XmlRequest, ShouldAuthorize
{
    use Authorized, OrdersAware, RequestCore;

    protected const METHOD = 'POST';
    protected const ADDRESS = 'info_report.php';

    /**
     * @JMS\SerializedName("ChangePeriod")
     * @JMS\Type("string")
     * @var ChangePeriod|null
     */
    protected $ChangePeriod;

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
