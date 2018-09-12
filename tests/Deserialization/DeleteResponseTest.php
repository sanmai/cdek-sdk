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

namespace Tests\Appwilio\CdekSDK\Deserialization;

use Appwilio\CdekSDK\Responses\DeleteResponse;
use Tests\Appwilio\CdekSDK\Fixtures\FixtureLoader;

/**
 * @covers \Appwilio\CdekSDK\Responses\DeleteResponse
 * @covers \Appwilio\CdekSDK\Common\DeliveryRequest
 * @covers \Appwilio\CdekSDK\Common\DeleteRequest
 * @covers \Appwilio\CdekSDK\Common\Order
 */
class DeleteResponseTest extends TestCase
{
    public function test_failing_request()
    {
        $response = $this->getSerializer()->deserialize(FixtureLoader::load('DeleteRequestSuccess.xml'), DeleteResponse::class, 'xml');

        /** @var $response DeleteResponse */
        $this->assertInstanceOf(DeleteResponse::class, $response);

        foreach ($response->getMessages() as $message) {
            $this->assertFalse($message->isError());
        }

        foreach ($response->getOrders() as $order) {
            $this->assertSame('TEST-123456', $order->getNumber());
        }
    }
}
