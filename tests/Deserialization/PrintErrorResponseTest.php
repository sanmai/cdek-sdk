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

use Appwilio\CdekSDK\Responses\PrintErrorResponse;
use Tests\Appwilio\CdekSDK\Fixtures\FixtureLoader;

/**
 * @covers \Appwilio\CdekSDK\Responses\PrintErrorResponse
 * @covers \Appwilio\CdekSDK\Responses\Types\PrintError
 * @covers \Appwilio\CdekSDK\Responses\Types\Message
 */
class PrintErrorResponseTest extends TestCase
{
    public function test_failing_request()
    {
        $response = $this->getSerializer()->deserialize(FixtureLoader::load('PrintErrorResponse.xml'), PrintErrorResponse::class, 'xml');

        /** @var $response PrintErrorResponse */
        $this->assertInstanceOf(PrintErrorResponse::class, $response);

        $this->assertNotEmpty($response->getErrors());

        foreach ($response->getMessages() as $message) {
            $this->assertTrue($message->isError());
            $this->assertContains('заказ не найден в базе', $message->getText());
            break;
        }
    }
}
