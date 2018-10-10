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

use CdekSDK\Responses\ScheduleResponse;
use Tests\CdekSDK\Fixtures\FixtureLoader;

/**
 * @covers \CdekSDK\Responses\ScheduleResponse
 * @covers \CdekSDK\Requests\ScheduleRequest
 * @covers \CdekSDK\Common\Order
 */
class ScheduleResponseTest extends TestCase
{
    private function loadFixture(string $filename): ScheduleResponse
    {
        return $this->getSerializer()->deserialize(FixtureLoader::load($filename), ScheduleResponse::class, 'xml');
    }

    public function test_successful_request()
    {
        $response = $this->loadFixture('ScheduleResponse.xml');

        $this->assertCount(1, $response->getMessages());
        $this->assertCount(0, $response->getErrors());
        $this->assertFalse($response->hasErrors());

        foreach ($response->getMessages() as $message) {
            $this->assertEmpty($message->getErrorCode());
        }
    }

    public function test_failing_request_client_deleted()
    {
        $response = $this->loadFixture('ScheduleResponseClientDeleted.xml');

        $this->assertCount(1, $response->getMessages());
        $this->assertCount(1, $response->getErrors());
        $this->assertTrue($response->hasErrors());
    }

    public function test_failing_request_missing_number()
    {
        $response = $this->loadFixture('ScheduleResponseMissingNumber.xml');

        $this->assertCount(1, $response->getMessages());
        $this->assertCount(1, $response->getErrors());
        $this->assertTrue($response->hasErrors());
    }

    public function test_failing_request_missing_order()
    {
        $response = $this->loadFixture('ScheduleResponseMissingOrder.xml');

        $this->assertCount(1, $response->getMessages());
        $this->assertCount(1, $response->getErrors());
        $this->assertTrue($response->hasErrors());
    }

    public function test_failing_request_missing_package()
    {
        $response = $this->loadFixture('ScheduleResponseMissingPackage.xml');

        $this->assertCount(1, $response->getMessages());
        $this->assertCount(1, $response->getErrors());
        $this->assertTrue($response->hasErrors());
    }

    public function test_it_serializes_to_empty_json()
    {
        $response = new ScheduleResponse();
        $this->assertSame([], $response->jsonSerialize());
    }
}
