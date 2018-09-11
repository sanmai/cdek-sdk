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
use GuzzleHttp\ClientInterface;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;

/**
 * @covers \Appwilio\CdekSDK\CdekClient
 */
class CdekClientTest extends TestCase
{
    private function getHttpClient($contentType, $responseBody)
    {
        $response = $this->createMock(ResponseInterface::class);
        $response->method('hasHeader')->willReturn($this->callback(function ($headerName) {
            return $headerName == 'Content-Type';
        }));
        $response->method('getHeader')->willReturn([$contentType]);
        $response->method('getBody')->willReturn($responseBody);

        $http = $this->createMock(ClientInterface::class);
        $http->method('request')->willReturn($response);

        return $http;
    }

    public function test_client_can_read_plain_text_response()
    {
        $client = new CdekClient('foo', 'bar', $this->getHttpClient('text/plain', 'testing'));
        $response = $client->sendPrintReceiptsRequest(new \Appwilio\CdekSDK\Requests\PrintReceiptsRequest());

        $this->assertSame('testing', $response->getBody());
    }
}
