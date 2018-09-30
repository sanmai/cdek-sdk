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

use JMS\Serializer\Annotation as JMS;

/**
 * Class DeleteRequest
 *
 * @JMS\XmlRoot(name="DeleteRequest")
 *
 * @package Appwilio\CdekSDK\Requests
 */
class DeleteRequest extends DeliveryRequest
{
    protected const ADDRESS = 'delete_orders.php';
}
