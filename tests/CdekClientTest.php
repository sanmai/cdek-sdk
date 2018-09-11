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
use GuzzleHttp\ClientInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * @covers \Appwilio\CdekSDK\CdekClient
 */
class CdekClientTest extends TestCase
{
    public function test_client_is_instantiable()
    {
        Assert::assertInstanceOf(
            CdekClient::class,
            $this->getClient()
        );
    }

    public function test_client_can_read_plain_text_response()
    {
        $textResponse = $this->createMock(ResponseInterface::class);
        $textResponse->method('hasHeader')->willReturn($this->callback(function ($headerName) {
            return $headerName == 'Content-Type';
        }));
        $textResponse->method('getHeader')->willReturn(['text/plain']);

        $http = $this->createMock(ClientInterface::class);
        $http->method('request')->willReturn($textResponse);

        /** @var HttpClient $mock */

        $client = new CdekClient('foo', 'bar', $http);
        $response = $client->sendCalculationRequest(new \Appwilio\CdekSDK\Requests\CalculationRequest());

        $this->assertSame($textResponse, $response);
    }

    private function getClient()
    {
        $mock = $this->createHttpClientMock();

        return new CdekClient('foo', 'bar', $mock);
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
