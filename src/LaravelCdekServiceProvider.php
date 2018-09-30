<?php

/**
 * This file is part of Cdek SDK package.
 *
 * © Appwilio (http://appwilio.com), greabock (https://github.com/greabock), JhaoDa (https://github.com/jhaoda)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Appwilio\CdekSDK;

use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use Doctrine\Common\Annotations\AnnotationRegistry;

class LaravelCdekServiceProvider extends ServiceProvider
{
    protected $defer = true;

    public function boot()
    {
        AnnotationRegistry::registerLoader('class_exists');
    }

    public function register()
    {
        $this->app->singleton(CdekClient::class, function (Application $app) {
            $config = $app['config']['services.cdek'];

            return new CdekClient($config['account'], $config['password'], $config['guzzle_options'] ?? []);
        });
    }

    public function provides()
    {
        return [CdekClient::class];
    }
}
