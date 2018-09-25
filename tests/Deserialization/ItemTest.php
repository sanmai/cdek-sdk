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

/**
 * @covers \CdekSDK\Common\Item
 */
class ItemTest extends TestCase
{
    public function test_example_item()
    {
        $item = $this->getSerializer()->deserialize('<Item WareKey="Ботинки60, размер 40" Comment="Кроссовки мужские" Cost="832" Payment="832" VATRate="VAT18" VATSum="126.92" Weight="0.560" Amount="1" DelivAmount="0" />', Item::class, 'xml');
        /** @var $item Item */
        $this->assertSame('Ботинки60, размер 40', $item->getWareKey());
        $this->assertSame('Кроссовки мужские', $item->getComment());
        $this->assertSame(832.0, $item->getCost());
        $this->assertSame(832.0, $item->getPayment());
        $this->assertSame('VAT18', $item->getVATRate());
        $this->assertSame(126.92, $item->getVATSum());
        $this->assertSame(0.560, $item->getWeight());
    }
}
