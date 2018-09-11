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

use Appwilio\CdekSDK\Responses\StatusReportResponse;
use Tests\Appwilio\CdekSDK\Fixtures\FixtureLoader;

/**
 * @covers \Appwilio\CdekSDK\Serialization\NullableDateTimeHandler
 */
class NullableDateTimeHandlerTest extends TestCase
{
    public function test_unserialize_normal_date()
    {
        $result = $this->getSerializer()->deserialize(FixtureLoader::load('StatusReportResponse.xml'), StatusReportResponse::class, 'xml');

        /** @var $result StatusReportResponse */
        $this->assertInstanceOf(StatusReportResponse::class, $result);
        $this->assertNull($result->getOrders()[0]->getDate());
    }
}
