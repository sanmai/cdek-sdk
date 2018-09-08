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

namespace Tests\Appwilio\CdekSDK;

use Appwilio\CdekSDK\CdekClient;
use GuzzleHttp\Client as HttpClient;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Appwilio\CdekSDK\CdekClient
 */
class CdekClientTestCase extends TestCase
{
    public function test_client_is_instantiable()
    {
        Assert::assertInstanceOf(
            CdekClient::class,
            $this->getClient()
        );
    }

    private function getClient()
    {
        $mock = $this->createHttpClientMock();

        return new class($mock) extends CdekClient {
            protected $httpClient;

            public function __construct($mock)
            {
                parent::__construct('foo', 'bar');

                $this->httpClient = $mock;
            }
        };
    }

    private function createHttpClientMock()
    {
        $mock = $this->getMockBuilder(HttpClient::class)
            ->setMethods(['request'])
            //->disableOriginalConstructor()
            ->getMock();

        //$mock->method('getTicket')->willReturn(new TicketResponse());
        //$mock->method('getResponseByTicket')->willReturn(new TrackingResponse());

        /** @var HttpClient $mock */
        return $mock;
    }
}
