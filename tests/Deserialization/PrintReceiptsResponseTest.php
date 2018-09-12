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

use Appwilio\CdekSDK\Responses\PrintReceiptsResponse;
use Tests\Appwilio\CdekSDK\Fixtures\FixtureLoader;

/**
 * @covers \Appwilio\CdekSDK\Responses\PrintReceiptsResponse
 * @covers \Appwilio\CdekSDK\Responses\Types\PrintReceiptsError
 * @covers \Appwilio\CdekSDK\Responses\Types\Message
 */
class PrintReceiptsResponseTest extends TestCase
{
    public function test_failing_request()
    {
        $response = $this->getSerializer()->deserialize(FixtureLoader::load('PrintReceiptsResponse.xml'), PrintReceiptsResponse::class, 'xml');

        /** @var $response PrintReceiptsResponse */
        $this->assertInstanceOf(PrintReceiptsResponse::class, $response);

        $this->assertNotEmpty($response->getErrors());

        foreach ($response->getMessages() as $message) {
            $this->assertTrue($message->isError());
            $this->assertContains('заказ не найден в базе', $message->getText());
            break;
        }
    }
}
