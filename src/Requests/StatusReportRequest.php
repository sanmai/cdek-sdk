<?php
/**
 * This code is licensed under the MIT License.
 *
 * Copyright (c) 2018 Appwilio (http://appwilio.com), greabock (https://github.com/greabock), JhaoDa (https://github.com/jhaoda)
 * Copyright (c) 2018 Alexey Kopytko <alexey@kopytko.com> and contributors
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

declare(strict_types=1);

namespace CdekSDK\Requests;

use CdekSDK\Common\ChangePeriod;
use CdekSDK\Common\Order;
use CdekSDK\Contracts\ShouldAuthorize;
use CdekSDK\Contracts\XmlRequest;
use CdekSDK\Requests\Concerns\Authorized;
use CdekSDK\Requests\Concerns\RequestCore;
use CdekSDK\Responses\StatusReportResponse;
use JMS\Serializer\Annotation as JMS;

/**
 * @deprecated https://github.com/cdek-it/sdk2.0
 *
 * Class StatusReportRequest.
 *
 * @JMS\XmlRoot(name="StatusReport")
 */
final class StatusReportRequest implements XmlRequest, ShouldAuthorize
{
    const METHOD = 'POST';
    const ADDRESS = '/status_report_h.php';
    const RESPONSE = StatusReportResponse::class;

    use Authorized;
    use RequestCore;

    /**
     * @JMS\XmlAttribute
     * @JMS\Type("int")
     *
     * @var int
     */
    protected $ShowHistory;

    /**
     * @JMS\XmlAttribute
     * @JMS\Type("int")
     *
     * @var int
     */
    protected $ShowReturnOrder;

    /**
     * @JMS\XmlAttribute
     * @JMS\Type("int")
     *
     * @var int
     */
    protected $ShowReturnOrderHistory;

    /**
     * @JMS\XmlList(inline = true, entry = "Order")
     * @JMS\Type("array<CdekSDK\Common\Order>")
     *
     * @var Order[]
     */
    protected $orders = [];

    /**
     * @JMS\SerializedName("ChangePeriod")
     *
     * @var ChangePeriod|null
     */
    protected $ChangePeriod;

    public function addOrder(Order $order)
    {
        $this->orders[$order->getId()] = $order;

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
}
