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

namespace Appwilio\CdekSDK\Requests;

use Appwilio\CdekSDK\Responses\DeleteResponse;
use JMS\Serializer\Annotation as JMS;

/**
 * Class DeleteRequest.
 *
 * @JMS\XmlRoot(name="DeleteRequest")
 */
final class DeleteRequest extends DeliveryRequest
{
    const ADDRESS = '/delete_orders.php';
    const RESPONSE = DeleteResponse::class;
}
