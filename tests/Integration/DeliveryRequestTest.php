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

namespace Tests\Appwilio\CdekSDK\Integration;

use Appwilio\CdekSDK\Requests\DeliveryRequest;

/**
 * @covers \Appwilio\CdekSDK\Requests\DeliveryRequest
 * @covers \Appwilio\CdekSDK\Responses\DeliveryResponse
 * @covers \Appwilio\CdekSDK\Common\DeliveryRequest
 */
class DeliveryRequestTest extends TestCase
{
    public function test_failing_request()
    {
        $request = new DeliveryRequest();
        $response = $this->getClient()->sendDeliveryRequest($request);
        $this->assertStringStartsWith('ERR_', $response->getRequests()[0]->getErrorCode());
    }
}
