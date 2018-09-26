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

namespace Tests\CdekSDK;

use CdekSDK\Requests\StatusReportRequest;
use CdekSDK\Responses\StatusReportResponse;
use CdekSDK\Serialization\Serializer;
use Doctrine\Common\Annotations\AnnotationRegistry;
use PHPUnit\Framework\TestCase;
use Tests\CdekSDK\Fixtures\FixtureLoader;

/**
 * @covers \CdekSDK\Serialization\Serializer
 */
class SerializerTest extends TestCase
{
    private function unconfigureAnnotationRegistry()
    {
        \Closure::bind(function () {
            AnnotationRegistry::$loaders = [];
        }, null, AnnotationRegistry::class)();
    }

    private function isAnnotationRegistryConfigured(): bool
    {
        return 'class_exists' == \Closure::bind(function () {
            return end(AnnotationRegistry::$loaders);
        }, null, AnnotationRegistry::class)();
    }

    public function test_it_configures_annotation_registry()
    {
        Serializer::doNotConfigureAnnotationRegistry();
        $this->unconfigureAnnotationRegistry();

        new Serializer();

        $this->assertFalse($this->isAnnotationRegistryConfigured());

        \Closure::bind(function () {
            Serializer::$configureAnnotationRegistry = true;
            Serializer::$annotationRegistryReady = false;
        }, null, Serializer::class)();

        new Serializer();

        $this->assertTrue($this->isAnnotationRegistryConfigured());
    }

    public function test_it_can_deserialize()
    {
        $serializer = new Serializer();
        $response = $serializer->deserialize(FixtureLoader::load('StatusReportResponse.xml'), StatusReportResponse::class, 'xml');

        assert($response instanceof StatusReportResponse); // PHPStan hint

        /** @var $response StatusReportResponse */
        $this->assertInstanceOf(StatusReportResponse::class, $response);
        $this->assertSame('1000028000', $response->getOrders()[0]->getDispatchNumber());
        $this->assertNull($response->getOrders()[0]->getDelayReason()->getDate());
    }

    public function test_it_can_serialize()
    {
        $serializer = new Serializer();
        $response = $serializer->serialize(new StatusReportRequest(), 'xml');

        $this->assertSame('<?xml version="1.0" encoding="UTF-8"?>
<StatusReport/>
', $response);
    }
}
