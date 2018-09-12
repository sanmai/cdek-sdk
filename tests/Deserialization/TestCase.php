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

use Appwilio\CdekSDK\Serialization\NullableDateTimeHandler;
use JMS\Serializer\Handler\HandlerRegistry;
use JMS\Serializer\Serializer;
use JMS\Serializer\SerializerBuilder;

abstract class TestCase extends \PHPUnit\Framework\TestCase
{
    private $serializer;

    protected function setUp()
    {
        $this->serializer = SerializerBuilder::create()->configureHandlers(function (HandlerRegistry $registry) {
            $registry->registerSubscribingHandler(new NullableDateTimeHandler());
        })->build();

        \Doctrine\Common\Annotations\AnnotationReader::addGlobalIgnoredName('phan');
    }

    protected function getSerializer(): Serializer
    {
        return $this->serializer;
    }
}
