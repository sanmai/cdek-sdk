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

use CdekSDK\Common\Item;
use CdekSDK\Serialization\Exception\LibXMLError;
use CdekSDK\Serialization\Exception\XmlErrorException;

/**
 * @covers \CdekSDK\Serialization\Serializer
 * @covers \CdekSDK\Serialization\Exception\LibXMLError
 * @covers \CdekSDK\Serialization\Exception\XmlErrorException
 */
class InvalidXMLTest extends TestCase
{
    public function test_it_throws_expected_type_of_exception()
    {
        $this->expectException(\JMS\Serializer\Exception\XmlErrorException::class);

        $this->getSerializer()->deserialize('', Item::class, 'xml');
    }

    public function test_error_contains_invalid_xml()
    {
        try {
            $this->getSerializer()->deserialize('foo<bar>', Item::class, 'xml');
        } catch (XmlErrorException $e) {
            $this->assertStringStartsWith('Expected valid XML', $e->getMessage());
            $this->assertContains('foo<bar>', $e->getMessage());
            $this->assertInstanceOf(\LibXMLError::class, $e->getXmlError());
            $this->assertSame(1, $e->getXmlError()->line);
            $this->assertInstanceOf(\JMS\Serializer\Exception\XmlErrorException::class, $e->getPrevious());
        }
    }

    public function test_it_copies_all_properties()
    {
        $error = new \LibXMLError();
        $error->message = 'foo';
        $error->line = 1;

        $newError = LibXMLError::fromLibXMLError($error, 'bar');

        $this->assertSame('foo', $newError->message);
        $this->assertSame(1, $newError->line);
        $this->assertSame('bar', $newError->xmlWithError);
    }
}
