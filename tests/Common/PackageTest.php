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

namespace Tests\CdekSDK\Common;

use CdekSDK\Common\Package;

/**
 * @covers \CdekSDK\Common\Package
 */
class PackageTest extends TestCase
{
    public function test_volume_weight()
    {
        $package = new Package([
            'SizeA' => 30,
            'SizeB' => 20,
            'SizeC' => 5,
        ]);

        $this->assertSame(30, $package->getSizeA());
        $this->assertSame(20, $package->getSizeB());
        $this->assertSame(5, $package->getSizeC());

        $this->assertSame(0.6, $package->calculateVolumeWeight());
    }

    /**
     * @deprecated
     */
    public function test_volume_deprecated_calculation()
    {
        $package = new Package([
            'SizeA' => 30,
            'SizeB' => 20,
            'SizeC' => 5,
        ]);

        $this->assertSame(0.6, $package->getVolumeWeight());
    }

    public function test_volume_as_is()
    {
        $package = new Package([
            'SizeA'        => 30,
            'SizeB'        => 20,
            'SizeC'        => 5,
            'VolumeWeight' => 1000.5,
        ]);

        $this->assertSame(1000.5, $package->getVolumeWeight());
    }

    public function test_get_set_weight()
    {
        $package = new Package([
            'Weight' => 100.1,
        ]);

        $this->assertSame(100.1, $package->getWeight());
    }
}
