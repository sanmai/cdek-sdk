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
use Appwilio\CdekSDK\Contracts\Request;
use Appwilio\CdekSDK\Requests\CalculationRequest;
use Appwilio\CdekSDK\Requests\PrintReceiptsRequest;
use Appwilio\CdekSDK\Requests\PvzListRequest;
use Appwilio\CdekSDK\Requests\StatusReportRequest;
use Appwilio\CdekSDK\Responses\CalculationResponse;
use Appwilio\CdekSDK\Responses\FileResponse;
use Appwilio\CdekSDK\Responses\PvzListResponse;
use Appwilio\CdekSDK\Responses\StatusReportResponse;
use GuzzleHttp\ClientInterface;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use Tests\Appwilio\CdekSDK\Fixtures\FixtureLoader;

/**
 * @covers \Appwilio\CdekSDK\CdekClient
 */
class CdekClientTest extends TestCase
{
    private function getHttpClient($contentType, $responseBody, $extraHeaders = [])
    {
        $extraHeaders['Content-Type'] = $contentType;

        $response = $this->createMock(ResponseInterface::class);
        $response->method('hasHeader')->will($this->returnCallback(function ($headerName) use ($extraHeaders) {
            return array_key_exists($headerName, $extraHeaders);
        }));
        $response->method('getHeader')->will($this->returnCallback(function ($headerName) use ($extraHeaders) {
            return [$extraHeaders[$headerName]];
        }));

        $stream = $this->createMock(StreamInterface::class);
        $stream->method('__toString')->willReturn($responseBody);
        $response->method('getBody')->willReturn($stream);

        $http = $this->createMock(ClientInterface::class);
        $http->method('request')->willReturn($response);

        return $http;
    }

    public function test_client_can_read_plain_text_response()
    {
        $client = new CdekClient('foo', 'bar', $this->getHttpClient('text/plain', 'testing'));
        $response = $client->sendPrintReceiptsRequest(new PrintReceiptsRequest());

        $this->assertSame('testing', (string) $response->getBody());
    }

    public function test_client_can_read_xml_response()
    {
        $client = new CdekClient('foo', 'bar', $this->getHttpClient('text/xml', FixtureLoader::load('StatusReportResponse.xml')));
        $response = $client->sendStatusReportRequest(new StatusReportRequest());

        /** @var $response StatusReportResponse */
        $this->assertInstanceOf(StatusReportResponse::class, $response);
        $this->assertSame('1000028000', $response->getOrders()[0]->getDispatchNumber());
    }

    public function test_client_can_read_json_response()
    {
        $client = new CdekClient('foo', 'bar', $this->getHttpClient('application/json', FixtureLoader::load('CalculationResponseError.json')));
        $response = $client->sendCalculationRequest(new CalculationRequest());

        /** @var $response CalculationResponse */
        $this->assertInstanceOf(CalculationResponse::class, $response);
        $this->assertTrue($response->hasErrors());
    }

    public function test_client_can_handle_param_request()
    {
        $client = new CdekClient('foo', 'bar', $this->getHttpClient('text/xml', FixtureLoader::load('PvzListEmpty.xml')));
        $response = $client->sendPvzListRequest(new PvzListRequest());

        /** @var $response PvzListResponse */
        $this->assertInstanceOf(PvzListResponse::class, $response);
        $this->assertEmpty($response->getItems());
    }

    public function test_client_can_handle_any_request()
    {
        $client = new CdekClient('foo', 'bar', $this->getHttpClient('text/plain', 'example'));
        $response = $client->sendRequest($this->createMock(Request::class));
        $this->assertInstanceOf(ResponseInterface::class, $response);
    }

    public function test_client_can_handle_attachments()
    {
        $client = new CdekClient('foo', 'bar', $this->getHttpClient('application/pdf', '%PDF', [
            'Content-Disposition' => 'attachment; filename=testing123.pdf',
        ]));
        $response = $client->sendRequest($this->createMock(Request::class));
        $this->assertInstanceOf(FileResponse::class, $response);

        $this->assertSame('%PDF', (string) $response->getBody());
    }

    public function test_fails_on_unknown_method()
    {
        $this->expectException(\BadMethodCallException::class);

        $invalid = 'invalid';
        (new CdekClient('foo', 'bar'))->{$invalid}();
    }
}
