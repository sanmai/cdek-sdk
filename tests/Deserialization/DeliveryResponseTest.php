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

use Appwilio\CdekSDK\Responses\DeliveryResponse;
use Tests\Appwilio\CdekSDK\Fixtures\FixtureLoader;

/**
 * @covers \Appwilio\CdekSDK\Responses\DeliveryResponse
 * @covers \Appwilio\CdekSDK\Common\DeliveryRequest
 */
class DeliveryResponseTest extends TestCase
{
    public function test_failing_request()
    {
        $response = $this->getSerializer()->deserialize(FixtureLoader::load('DeliveryRequestFailed.xml'), DeliveryResponse::class, 'xml');

        /** @var $response DeliveryResponse */
        $this->assertInstanceOf(DeliveryResponse::class, $response);

        foreach ($response->getMessages() as $message) {
            $this->assertTrue($message->isError());
        }
    }
}
