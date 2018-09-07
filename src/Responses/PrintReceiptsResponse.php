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
use Appwilio\CdekSDK\Responses\Types\PrintReceiptsError;
use JMS\Serializer\Annotation as JMS;

/**
 * Class PrintReceiptsResponse
 *
 * @package Appwilio\CdekSDK\Responses
 */
class PrintReceiptsResponse
{
    /**
     * @JMS\XmlList(entry = "Order", inline = true)
     * @JMS\Type("array<Appwilio\CdekSDK\Responses\Types\PrintReceiptsError>")
     *
     * @var array|Order[]
     */
    private $orders = [];

    /**
     * @JMS\XmlList(entry = "OrdersPrint", inline = true)
     * @JMS\Type("array<Appwilio\CdekSDK\Responses\Types\PrintReceiptsError>")
     *
     * @var array|Order[]
     */
    private $ordersPrint = [];

    /**
     * @JMS\Exclude
     *
     * @var PrintReceiptsError[]
     */
    protected $errors;

    /**
     * @JMS\PostDeserialize
     */
    public function filter()
    {
        $this->errors = array_merge($this->orders, $this->ordersPrint);

        unset($this->orders, $this->ordersPrint);
    }

    /**
     * @return PrintReceiptsError[]
     */
    public function getErrors()
    {
        return $this->errors;
    }
}
