<?php
/*
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

use CdekSDK\CdekClient;
use CdekSDK\LaravelCdekServiceProvider;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Illuminate\Contracts\Foundation\Application as ApplicationInterface;
use PHPUnit\Framework\TestCase;

/**
 * @covers \CdekSDK\LaravelCdekServiceProvider
 */
class LaravelCdekServiceProviderTest extends TestCase
{
    /**
     * @var ApplicationInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    private $app;

    /**
     * @var LaravelCdekServiceProvider
     */
    private $provider;

    protected function setUp()
    {
        parent::setUp();

        $this->app = $this->createMock(ApplicationInterface::class);
        $this->provider = new LaravelCdekServiceProvider($this->app);
    }

    public function testBoot()
    {
        $this->provider->boot();

        $this->assertEquals('class_exists', \Closure::bind(function () {
            return end(AnnotationRegistry::$loaders);
        }, null, AnnotationRegistry::class)());
    }

    public function testRegister()
    {
        $this->app->expects($this->once())
            ->method('singleton')
            ->with(CdekClient::class);

        $this->provider->register();
    }
}
