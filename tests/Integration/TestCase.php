<?php
/*
 * This file is part of Cdek SDK package.
 *
 * © Appwilio (http://appwilio.com), greabock (https://github.com/greabock), JhaoDa (https://github.com/jhaoda)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Tests\Appwilio\CdekSDK\Integration;

use Appwilio\CdekSDK\CdekClient;
use GuzzleHttp\Client as GuzzleClient;

abstract class TestCase extends \PHPUnit\Framework\TestCase
{
    private $client;

    protected function setUp()
    {
        if (false === getenv('CDEK_ACCOUNT')) {
            $this->markTestSkipped('Integration testing disabled (CDEK_ACCOUNT missing).');
        }

        if (false === getenv('CDEK_PASSWORD')) {
            $this->markTestSkipped('Integration testing disabled (CDEK_PASSWORD missing).');
        }

        $http = false === getenv('CDEK_BASE_URL') ? null : new GuzzleClient([
            'base_uri' => getenv('CDEK_BASE_URL'),
        ]);

        $this->client = new CdekClient(getenv('CDEK_ACCOUNT'), getenv('CDEK_PASSWORD'), $http);
    }

    protected function getClient(): CdekClient
    {
        return $this->client;
    }
}
