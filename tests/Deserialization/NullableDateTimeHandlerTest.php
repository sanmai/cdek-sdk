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

namespace Tests\CdekSDK\Deserialization;

use CdekSDK\Common\Order;
use CdekSDK\Responses\StatusReportResponse;
use CdekSDK\Serialization\NullableDateTimeHandler;
use JMS\Serializer\Exception\RuntimeException;
use JMS\Serializer\Naming\IdenticalPropertyNamingStrategy;
use JMS\Serializer\XmlDeserializationVisitor;
use Tests\CdekSDK\Fixtures\FixtureLoader;

/**
 * @covers \CdekSDK\Serialization\NullableDateTimeHandler
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

    public function test_it_reads_response_with_delivery_date_without_time()
    {
        $response = $this->getSerializer()->deserialize(FixtureLoader::load('StatusReportResponseDateOnly.xml'), StatusReportResponse::class, 'xml');

        /** @var $response StatusReportResponse */
        $this->assertInstanceOf(StatusReportResponse::class, $response);

        $this->assertCount(2, $response->getOrders());

        $order = $response->getOrders()[0];
        $this->assertInstanceOf(Order::class, $order);

        $this->assertSame('2018-04-06 13:33:27', $order->getDeliveryDate()->format('Y-m-d H:i:s'));

        $order = $response->getOrders()[1];
        $this->assertInstanceOf(Order::class, $order);

        $this->assertSame('2011-04-07 00:00:00', $order->getDeliveryDate()->format('Y-m-d H:i:s'));
    }

    public function test_fails_on_invalid_date()
    {
        $this->expectException(RuntimeException::class);
        $this->expectException(\JMS\Serializer\Exception\RuntimeException::class);

        $this->getSerializer()->deserialize('<Order DeliveryDate="00:00:00" />', Order::class, 'xml');
    }

    public function test_fails_on_unexpected_date_format()
    {
        $this->expectException(RuntimeException::class);
        $this->expectException(\JMS\Serializer\Exception\RuntimeException::class);
        $this->expectExceptionMessageRegExp('/^Failed to deserialize Date="2000-01-01 00:00:00": .* expected format/');

        $this->getSerializer()->deserialize('<Order Date="2000-01-01 00:00:00" />', Order::class, 'xml');
    }

    public function test_do_not_resets_time_if_not_needed()
    {
        $handler = new NullableDateTimeHandler();
        $visitor = new XmlDeserializationVisitor(new IdenticalPropertyNamingStrategy());
        $sxe = new \SimpleXMLElement('<date>2000-01-01_</date>');

        if (date('H:i:s') === '00:00:00') {
            sleep(1);
        }

        $date = $handler->deserializeDateTimeImmutableFromXml($visitor, $sxe, [
            'name'   => \DateTimeImmutable::class,
            'params' => [
                0 => 'Y-m-d\\TH:i:sP',
                1 => '',
                //2 => 'Y-m-d\\TH:i:sP',
                3 => 'Y-m-d_',
            ],
        ]);

        $this->assertNotSame('2000-01-01 00:00:00', $date->format('Y-m-d H:i:s'));
    }
}
