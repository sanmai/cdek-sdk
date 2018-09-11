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

namespace Tests\Appwilio\CdekSDK;

use Appwilio\CdekSDK\Responses\StatusReportResponse;
use Appwilio\CdekSDK\Serialization\NullableDateTimeHandler;
use JMS\Serializer\Handler\HandlerRegistry;
use JMS\Serializer\SerializerBuilder;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Appwilio\CdekSDK\Serialization\NullableDateTimeHandler
 */
class NullableDateTimeHandlerTest extends TestCase
{
    private $serializer;

    protected function setUp()
    {
        $this->serializer = SerializerBuilder::create()->configureHandlers(function (HandlerRegistry $registry) {
            $registry->registerSubscribingHandler(new NullableDateTimeHandler());
        })->build();
    }

    public function test_unserialize_normal_date()
    {
        \Doctrine\Common\Annotations\AnnotationRegistry::registerLoader('class_exists');
        \Doctrine\Common\Annotations\AnnotationReader::addGlobalIgnoredName('suppress');

        $result = $this->serializer->deserialize(file_get_contents(__DIR__.'/Fixtures/data/StatusReportResponse.xml'), StatusReportResponse::class, 'xml');

        /** @var $result StatusReportResponse */
        $this->assertInstanceOf(StatusReportResponse::class, $result);
        $this->assertNull($result->getOrders()[0]->getDate());
    }
}
