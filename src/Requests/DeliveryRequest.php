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

use CdekSDK\Common\Address;
use CdekSDK\Common\CallCourier;
use CdekSDK\Common\Fillable;
use CdekSDK\Common\Order;
use CdekSDK\Contracts\ShouldAuthorize;
use CdekSDK\Contracts\XmlRequest;
use CdekSDK\Requests\Concerns\Authorized;
use CdekSDK\Requests\Concerns\OrdersAware;
use CdekSDK\Requests\Concerns\RequestCore;
use CdekSDK\Responses\DeliveryResponse;
use JMS\Serializer\Annotation as JMS;

/**
 * @deprecated https://github.com/cdek-it/sdk2.0
 *
 * Class DeliveryRequest.
 * @see DeleteRequest
 *
 * @JMS\XmlRoot(name="DeliveryRequest")
 *
 * @final
 */
class DeliveryRequest implements XmlRequest, ShouldAuthorize
{
    use Authorized;
    use Fillable;
    use OrdersAware;
    use RequestCore;

    const METHOD = 'POST';
    const ADDRESS = '/new_orders.php';
    const RESPONSE = DeliveryResponse::class;

    /**
     * @JMS\XmlAttribute
     * @JMS\Type("string")
     *
     * @var string
     */
    protected $DeveloperKey;

    /**
     * @JMS\XmlAttribute
     * @JMS\Type("string")
     *
     * @var string
     */
    protected $Number;

    /**
     * @JMS\XmlAttribute
     * @JMS\Type("bool")
     *
     * @var bool
     */
    protected $ForeignDelivery;

    /**
     * @JMS\XmlAttribute
     * @JMS\Type("string")
     *
     * @var string
     */
    protected $Currency;

    /**
     * @JMS\Type("CdekSDK\Requests\CallCourierRequest")
     *
     * @var CallCourierRequest
     */
    protected $CallCourier;

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

    /** @return static */
    public function addOrder(Order $order)
    {
        // При создании заказа идентификация идёт только по номеру, это обязательный параметр
        $this->orders[$order->getNumber()] = $order;

        return $this;
    }

    public function setCallCourier(CallCourier $call, Address $address)
    {
        /*
         * СДЭК требует чтобы при оформлении заказа адрес был в поле SendAddress,
         * а не в поле Address как при оформлении заявки на вызов курьера. При этом
         * сам адрес является обязательным.
         *
         * Спрячем эти неочевидные детали от пользователя, указав адрес сами.
         */

        $call->setSendAddress($address);

        $this->CallCourier = new CallCourierRequest();
        $this->CallCourier->addCall($call);

        return $this;
    }
}
