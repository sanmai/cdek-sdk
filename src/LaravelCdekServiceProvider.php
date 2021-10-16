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

namespace CdekSDK;

use CdekSDK\Serialization\Serializer;
use Doctrine\Common\Annotations\AnnotationRegistry;
use GuzzleHttp\Client as GuzzleClient;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;

/**
 * @deprecated https://github.com/cdek-it/sdk2.0
 *
 * @final
 */
class LaravelCdekServiceProvider extends ServiceProvider
{
    protected $defer = true;

    const SKIP_SERIALIZER_CONFIGURATION_FUNC = [Serializer::class, 'doNotConfigureAnnotationRegistry'];

    public function boot()
    {
        // @codeCoverageIgnoreStart
        /** @phan-suppress-next-line PhanUndeclaredStaticMethodInCallable */
        if (\is_callable(self::SKIP_SERIALIZER_CONFIGURATION_FUNC)) {
            /** @phan-suppress-next-line PhanDeprecatedFunction */
            AnnotationRegistry::registerLoader('class_exists');
            /** @phan-suppress-next-line PhanUndeclaredStaticMethodInCallable */
            \call_user_func(self::SKIP_SERIALIZER_CONFIGURATION_FUNC);
        }
        // @codeCoverageIgnoreEnd
    }

    /**
     * @psalm-suppress MixedAssignment
     * @psalm-suppress MixedArrayAccess
     * @psalm-suppress MixedArgument
     * @psalm-suppress MixedOperand
     */
    public function register()
    {
        $this->app->singleton(CdekClient::class, function (Application $app) {
            $config = $app['config']['services.cdek'];

            $client = !empty($config['guzzle_options']) ? new GuzzleClient($config['guzzle_options'] + [
                'base_uri' => CdekClient::STANDARD_BASE_URL,
                'timeout'  => CdekClient::DEFAULT_TIMEOUT,
            ]) : null;

            return new CdekClient($config['account'], $config['password'], $client);
        });
    }

    public function provides()
    {
        return [CdekClient::class];
    }
}
