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

use CdekSDK\Responses\CallCourierResponse;
use CdekSDK\Responses\Types\Message;
use Tests\CdekSDK\Fixtures\FixtureLoader;

/**
 * @covers \CdekSDK\Responses\CallCourierResponse
 * @covers \CdekSDK\Responses\Types\Result
 * @covers \CdekSDK\Responses\Types\Error
 */
class CallCourierResponseTest extends TestCase
{
    public function test_it_deserializes()
    {
        $response = $this->getSerializer()->deserialize(FixtureLoader::load('CallCourierResponse.xml'), CallCourierResponse::class, 'xml');

        $number = $message = null;

        /** @var CallCourierResponse $response */
        foreach ($response->getNumbers() as $number) {
            $this->assertSame('5296692', $number);
        }

        $this->assertCount(1, $response->getNumbers());

        foreach ($response->getMessages() as $message) {
            $this->assertFalse($message->isError());
            $this->assertSame('Добавлено заказов 1', $message->getText());
        }

        $this->assertCount(1, $response->getMessages());

        $this->assertFalse($response->hasErrors());
        foreach ($response->getErrors() as $message) {
            $this->fail();
        }
    }

    public function test_it_deserializes_errors()
    {
        $response = $this->getSerializer()->deserialize(FixtureLoader::load('CallCourierResponseError.xml'), CallCourierResponse::class, 'xml');

        /** @var CallCourierResponse $response */
        foreach ($response->getNumbers() as $number) {
            $this->fail("Unexpected number: {$number}");
        }

        $this->assertCount(8, $response->getMessages());

        foreach ($response->getMessages() as $message) {
            $this->assertTrue($message->isError());
            $this->assertSame('Отсутствие обязательного атрибута: SendCityCode', $message->getText());
            $this->assertSame('ERR_NEED_ATTRIBUTE', $message->getCode());
            break;
        }

        $this->assertTrue(isset($message));

        $this->assertTrue($response->hasErrors());
        $this->assertCount(8, $response->getErrors());

        foreach ($response->getErrors() as $message) {
            $this->assertTrue($message->isError());
        }

        if (isset($message)) {
            /** @var $message Message */
            $this->assertSame('Интервал ожидания курьера между TimeBeg и TimeEnd должен составлять не менее 3 непрерывных часов', $message->getText());
            $this->assertSame('ERR_CALLCOURIER_TIME_INTERVAL', $message->getCode());
        }
    }

    public function test_it_deserializes_failed_response()
    {
        $response = $this->getSerializer()->deserialize(FixtureLoader::load('CallCourierResponseErrorMissingCall.xml'), CallCourierResponse::class, 'xml');
        $this->assertTrue($response->hasErrors());
        $this->assertCount(1, $response->getErrors());

        foreach ($response->getErrors() as $message) {
            $this->assertSame('Не найден обязательный тег:CALL', $message->getText());
            $this->assertSame('ERR_NOTFOUNDTAG', $message->getCode());
        }
    }
}
