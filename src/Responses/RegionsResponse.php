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

namespace CdekSDK\Responses;

use CdekSDK\Common\Region;
use CdekSDK\Contracts\Response;
use JMS\Serializer\Annotation as JMS;

/**
 * Class RegionsResponse.
 *
 * @template-implements IteratorAggregate<Common\Region>
 */
final class RegionsResponse implements \IteratorAggregate, Response
{
    /**
     * @JMS\XmlList(entry = "Region", inline = true)
     * @JMS\Type("array<CdekSDK\Common\Region>")
     *
     * @var Region[]
     */
    private $items = [];

    /**
     * @return Region[]
     */
    public function getItems(): array
    {
        return $this->items;
    }

    /**
     * @phan-suppress PhanUnextractableAnnotationSuffix
     *
     * @return \ArrayIterator<array-key, Region>
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->getItems());
    }

    public function jsonSerialize()
    {
        return [];
    }

    public function hasErrors(): bool
    {
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function getMessages()
    {
        return [];
    }
}
