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

namespace Tests\CdekSDK;

use CdekSDK\CdekClient;
use CdekSDK\Contracts\DateAware;
use CdekSDK\Contracts\ParamRequest;
use CdekSDK\Contracts\Request;
use CdekSDK\Contracts\Response;
use CdekSDK\Contracts\ShouldAuthorize;
use CdekSDK\Contracts\XmlRequest;
use CdekSDK\Requests\CalculationRequest;
use CdekSDK\Requests\CitiesRequest;
use CdekSDK\Requests\InfoReportRequest;
use CdekSDK\Requests\PrintReceiptsRequest;
use CdekSDK\Requests\PvzListRequest;
use CdekSDK\Requests\RegionsRequest;
use CdekSDK\Requests\StatusReportRequest;
use CdekSDK\Responses\CalculationResponse;
use CdekSDK\Responses\ErrorResponse;
use CdekSDK\Responses\FileResponse;
use CdekSDK\Responses\InfoReportResponse;
use CdekSDK\Responses\JsonErrorResponse;
use CdekSDK\Responses\PvzListResponse;
use CdekSDK\Responses\StatusReportResponse;
use CdekSDK\Serialization\Exception\XmlErrorException;
use Gamez\Psr\Log\TestLogger;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\ServerException;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Log\LogLevel;
use Tests\CdekSDK\Fixtures\FixtureLoader;

/**
 * @covers \CdekSDK\CdekClient
 */
class CdekClientTest extends TestCase
{
    private $lastRequestOptions = [];

    private function getHttpClient($contentType, $responseBody, $extraHeaders = [])
    {
        $extraHeaders['Content-Type'] = $contentType;

        $response = $this->createMock(ResponseInterface::class);
        $response->method('hasHeader')->will($this->returnCallback(function ($headerName) use ($extraHeaders) {
            return \array_key_exists($headerName, $extraHeaders);
        }));
        $response->method('getHeader')->will($this->returnCallback(function ($headerName) use ($extraHeaders) {
            return [$extraHeaders[$headerName]];
        }));

        $stream = $this->createMock(StreamInterface::class);
        $stream->method('__toString')->willReturn($responseBody);
        $response->method('getBody')->willReturn($stream);

        $http = $this->createMock(ClientInterface::class);
        $http->method('request')->will($this->returnCallback(function ($method, $address, array $options) use ($response) {
            $this->lastRequestOptions = $options;

            return $response;
        }));

        return $http;
    }

    public function test_client_can_read_plain_text_response()
    {
        $client = new CdekClient('foo', 'bar', $this->getHttpClient('text/plain', 'testing'));
        $response = $client->sendPrintReceiptsRequest(new PrintReceiptsRequest());

        $this->assertSame('testing', (string) $response->getBody());

        $this->assertArrayHasKey('form_params', $this->lastRequestOptions);
        $this->assertArrayHasKey('xml_request', $this->lastRequestOptions['form_params']);
    }

    public function test_client_can_read_xml_response()
    {
        $client = new CdekClient('foo', 'bar', $this->getHttpClient('text/xml', FixtureLoader::load('StatusReportResponse.xml')));
        $response = $client->sendStatusReportRequest(new StatusReportRequest());

        /** @var $response StatusReportResponse */
        $this->assertInstanceOf(StatusReportResponse::class, $response);
        $this->assertSame('1000028000', $response->getOrders()[0]->getDispatchNumber());

        $this->assertArrayHasKey('form_params', $this->lastRequestOptions);
        $this->assertArrayHasKey('xml_request', $this->lastRequestOptions['form_params']);
    }

    public function test_client_can_read_xml_response_with_alternative_content_type()
    {
        $client = new CdekClient('foo', 'bar', $this->getHttpClient('application/xml', FixtureLoader::load('StatusReportResponse.xml')));
        $response = $client->sendStatusReportRequest(new StatusReportRequest());

        /** @var $response StatusReportResponse */
        $this->assertInstanceOf(StatusReportResponse::class, $response);
    }

    public function test_client_can_read_json_response()
    {
        $client = new CdekClient('foo', 'bar', $this->getHttpClient('application/json', FixtureLoader::load('CalculationResponseError.json')));
        $response = $client->sendCalculationRequest(new CalculationRequest());

        /** @var $response CalculationResponse */
        $this->assertInstanceOf(CalculationResponse::class, $response);
        $this->assertTrue($response->hasErrors());

        $this->assertArrayHasKey('body', $this->lastRequestOptions);
        $this->assertArrayHasKey('headers', $this->lastRequestOptions);
        $this->assertArrayHasKey('Content-Type', $this->lastRequestOptions['headers']);
        $this->assertSame('application/json', $this->lastRequestOptions['headers']['Content-Type']);
    }

    public function test_client_can_handle_param_request()
    {
        $client = new CdekClient('foo', 'bar', $this->getHttpClient('text/xml', FixtureLoader::load('PvzListEmpty.xml')));
        $response = $client->sendPvzListRequest(new PvzListRequest());

        /** @var $response PvzListResponse */
        $this->assertInstanceOf(PvzListResponse::class, $response);
        $this->assertEmpty($response->getItems());

        $this->assertArrayHasKey('query', $this->lastRequestOptions);
    }

    public function test_client_can_handle_any_request()
    {
        $client = new CdekClient('foo', 'bar', $this->getHttpClient('text/plain', 'example'));
        $response = $client->sendRequest($this->createMock(Request::class));
        $this->assertInstanceOf(ResponseInterface::class, $response);

        $this->assertEmpty($this->lastRequestOptions);
    }

    public function test_client_can_handle_attachments()
    {
        $client = new CdekClient('foo', 'bar', $this->getHttpClient('application/pdf', '%PDF', [
            'Content-Disposition' => 'attachment; filename=testing123.pdf',
        ]));
        $response = $client->sendRequest($this->createMock(Request::class));
        $this->assertInstanceOf(FileResponse::class, $response);

        \assert($response instanceof FileResponse);

        $this->assertSame('%PDF', (string) $response->getBody());
        $this->assertEmpty($this->lastRequestOptions);
    }

    public function test_client_can_log_response()
    {
        $client = new CdekClient('foo', 'bar', $this->getHttpClient('text/xml', FixtureLoader::load('InfoReportFailed.xml')));
        $client->setLogger($logger = new TestLogger());

        $response = $client->sendInfoReportRequest(new InfoReportRequest());

        /** @var $response InfoReportResponse */
        $this->assertInstanceOf(InfoReportResponse::class, $response);
        $this->assertSame(3, $logger->log->countRecordsWithLevel(LogLevel::DEBUG));
        $this->assertTrue($logger->log->hasRecordsWithMessage(FixtureLoader::load('InfoReportFailed.xml')));
        $this->assertTrue($logger->log->hasRecordsWithPartialMessage('<InfoRequest'));

        $this->assertSame(1, $logger->log->countRecordsWithContextKey('method'));
        $this->assertSame(1, $logger->log->countRecordsWithContextKey('location'));
    }

    public function test_client_can_pass_through_common_exceptions()
    {
        $client = new CdekClient('foo', 'bar', $http = $this->getHttpClient('text/xml', FixtureLoader::load('InfoReportFailed.xml')));

        $http->method('request')->will($this->returnCallback(function () {
            throw new \RuntimeException();
        }));

        $this->expectException(\RuntimeException::class);
        $response = $client->sendInfoReportRequest(new InfoReportRequest());
    }

    public function test_client_can_pass_through_exceptions_without_response()
    {
        $client = new CdekClient('foo', 'bar', $http = $this->getHttpClient('text/xml', FixtureLoader::load('InfoReportFailed.xml')));

        $http->method('request')->will($this->returnCallback(function () {
            throw new ServerException('', $this->createMock(RequestInterface::class));
        }));

        $this->expectException(ServerException::class);
        $response = $client->sendInfoReportRequest(new InfoReportRequest());
    }

    public function test_client_can_handle_empty_response_without_throw()
    {
        $client = new CdekClient('foo', 'bar', $http = $this->getHttpClient('text/xml', ''));

        $response = $client->sendInfoReportRequest(new InfoReportRequest());

        $this->assertInstanceOf(ErrorResponse::class, $response);
    }

    public function test_client_can_handle_error_response()
    {
        $client = new CdekClient('foo', 'bar', $http = $this->getHttpClient('text/xml', FixtureLoader::load('InfoReportFailed.xml')));
        $client->setLogger($logger = new TestLogger());

        $responseMock = $this->createMock(ResponseInterface::class);
        $responseMock->method('getStatusCode')->willReturn(500);
        $responseMock->method('getReasonPhrase')->willReturn('Server error');

        $http->method('request')->will($this->returnCallback(function () use ($responseMock) {
            throw new ServerException('', $this->createMock(RequestInterface::class), $responseMock);
        }));

        //$this->expectException(\RuntimeException::class);
        $response = $client->sendInfoReportRequest(new InfoReportRequest());
        $this->assertSame(3, $logger->log->countRecordsWithLevel(LogLevel::DEBUG));
        $this->assertTrue($logger->log->hasRecordsWithPartialMessage('CDEK API responded with an HTTP error code'));

        $this->assertSame(1, $logger->log->countRecordsWithContextKey('exception'));
        $this->assertSame(1, $logger->log->countRecordsWithContextKey('error_code'));

        $this->assertInstanceOf(Response::class, $response);

        $this->assertTrue($response->hasErrors());
        $this->assertCount(1, $response->getMessages());
        foreach ($response->getMessages() as $message) {
            $this->assertSame('500', $message->getErrorCode());
            $this->assertSame('Server error', $message->getMessage());
        }

        $this->assertInstanceOf(ResponseInterface::class, $response);

        \assert($response instanceof ResponseInterface);
        $this->assertSame(500, $response->getStatusCode());
        $this->assertSame('Server error', $response->getReasonPhrase());
    }

    public function test_client_can_handle_json_instead_of_xml()
    {
        $client = new CdekClient('foo', 'bar', $this->getHttpClient('application/json', FixtureLoader::load('ServiceUnavailableResponse.json')));

        $response = $client->sendRegionsRequest(new RegionsRequest());

        $this->assertInstanceOf(JsonErrorResponse::class, $response);

        $this->assertTrue($response->hasErrors());

        foreach ($response->getMessages() as $message) {
            $this->assertSame('Service Unavailable', $message->getMessage());
            $this->assertSame('503', $message->getErrorCode());
        }
    }

    public function test_client_can_handle_different_json_instead_of_xml()
    {
        $client = new CdekClient('foo', 'bar', $this->getHttpClient('application/json', FixtureLoader::load('CitiesResponseJsonError.json')));

        $response = $client->sendCitiesRequest(new CitiesRequest());

        $this->assertInstanceOf(JsonErrorResponse::class, $response);

        $this->assertTrue($response->hasErrors());

        foreach ($response->getMessages() as $message) {
            $this->assertSame('danger', $message->getMessage());
            $this->assertSame('internal_error', $message->getErrorCode());
        }
    }

    public function test_client_rethrows_xml_error_if_invalid_json()
    {
        $client = new CdekClient('foo', 'bar', $this->getHttpClient('application/json', '{'.FixtureLoader::load('ServiceUnavailableResponse.json')));

        $this->expectException(XmlErrorException::class);

        $client->sendRegionsRequest(new RegionsRequest());
    }

    public function test_client_throws_xml_error_if_not_json()
    {
        $client = new CdekClient('foo', 'bar', $this->getHttpClient('application/json', '<'));

        $this->expectException(XmlErrorException::class);

        $client->sendRegionsRequest(new RegionsRequest());
    }

    public function test_fails_on_unknown_method()
    {
        $this->expectException(\BadMethodCallException::class);

        $invalid = 'invalid';
        (new CdekClient('foo', 'bar'))->{$invalid}();
    }

    public function test_it_adds_signature()
    {
        $request = new class() implements XmlRequest, ShouldAuthorize {
            public function getMethod(): string
            {
                return '';
            }

            public function getAddress(): string
            {
                return '';
            }

            public function getResponseClassName(): string
            {
                return '';
            }

            public function getSerializationFormat(): string
            {
                return '';
            }

            public function date(\DateTimeInterface $date): ShouldAuthorize
            {
                return $this;
            }

            public function credentials(string $account, string $secure): ShouldAuthorize
            {
                throw new \LogicException($secure);
            }
        };

        try {
            $client = new CdekClient('f62dcb094cc91617def72d9c260b4483', '6bd3937dcebd15beb25278bc0657014c');
            $client->sendRequest($request, new \DateTime('2016-10-31'));
        } catch (\LogicException $e) {
            $this->assertSame('4368247f1ff152d273a29fac6993a94a', $e->getMessage());
        }
    }

    public function test_param_post_request()
    {
        $request = new class() implements ParamRequest {
            public function getMethod(): string
            {
                return 'POST';
            }

            public function getAddress(): string
            {
                return '';
            }

            public function getResponseClassName(): string
            {
                return '';
            }

            public function getSerializationFormat(): string
            {
                return '';
            }

            public function getParams(): array
            {
                return [
                    'foo' => 'bar',
                ];
            }
        };

        $client = new CdekClient('', '', $this->getHttpClient('text/plain', 'example'));
        $client->sendRequest($request);

        $this->assertArrayHasKey('form_params', $this->lastRequestOptions);
        $this->assertSame('bar', $this->lastRequestOptions['form_params']['foo']);
    }

    public function test_it_sets_custom_user_agent()
    {
        CdekClient::setUserAgent('FooBar', '1.2.3');

        $reflectionClass = new \ReflectionClass(CdekClient::class);
        $reflectionProperty = $reflectionClass->getProperty('http');
        $reflectionProperty->setAccessible(true);

        $value = $reflectionProperty->getValue(new CdekClient('', ''));

        $reflectionClass = new \ReflectionClass(CdekClient::class);
        $reflectionProperty = $reflectionClass->getProperty('userAgentPostfix');
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue(CdekClient::class, null);

        /** @var ClientInterface $value */
        $this->assertContains('FooBar', $value->getConfig()['headers']['User-Agent']);
    }

    /**
     * @depends test_it_sets_custom_user_agent
     */
    public function test_it_sets_default_user_agent()
    {
        $reflectionClass = new \ReflectionClass(CdekClient::class);
        $reflectionProperty = $reflectionClass->getProperty('http');
        $reflectionProperty->setAccessible(true);

        $value = $reflectionProperty->getValue(new CdekClient('', ''));

        /** @var ClientInterface $value */
        $this->assertContains(CdekClient::PACKAGE_NAME, $value->getConfig()['headers']['User-Agent']);

        $this->assertArrayHasKey('base_uri', $value->getConfig());
    }

    public function test_it_adds_date_and_secure()
    {
        $request = new class() implements ParamRequest, ShouldAuthorize {
            public function getMethod(): string
            {
                return 'POST';
            }

            public function getAddress(): string
            {
                return '';
            }

            public function getResponseClassName(): string
            {
                return '';
            }

            public function getSerializationFormat(): string
            {
                return '';
            }

            private $params = [];

            public function getParams(): array
            {
                return $this->params;
            }

            public function date(\DateTimeInterface $date): ShouldAuthorize
            {
                $this->params['date'] = $date->format('Y-m-d');

                return $this;
            }

            public function credentials(string $account, string $secure): ShouldAuthorize
            {
                $this->params['account'] = $account;
                $this->params['secure'] = $secure;

                return $this;
            }
        };

        $client = new CdekClient('foo', 'bar', $this->getHttpClient('text/plain', 'example'));
        $client->sendRequest($request, new \DateTimeImmutable('2018-10-13'));

        $this->assertArrayHasKey('form_params', $this->lastRequestOptions);
        $this->assertSame('2018-10-13', $this->lastRequestOptions['form_params']['date']);
        $this->assertSame('foo', $this->lastRequestOptions['form_params']['account']);
        $this->assertSame('d7fe9d0ad9901440c4cba573cfc0c86e', $this->lastRequestOptions['form_params']['secure']);
    }

    public function test_it_loads_date_from_capable_request()
    {
        $request = new class() implements ParamRequest, ShouldAuthorize, DateAware {
            public function getMethod(): string
            {
                return 'POST';
            }

            public function getAddress(): string
            {
                return '';
            }

            public function getResponseClassName(): string
            {
                return '';
            }

            public function getSerializationFormat(): string
            {
                return '';
            }

            private $params = [];

            public function getParams(): array
            {
                return $this->params;
            }

            public function date(\DateTimeInterface $date): ShouldAuthorize
            {
                $this->params['date'] = $date->format('Y-m-d');

                return $this;
            }

            public function credentials(string $account, string $secure): ShouldAuthorize
            {
                $this->params['account'] = $account;
                $this->params['secure'] = $secure;

                return $this;
            }

            public function getRequestDate(): \DateTimeInterface
            {
                return new \DateTimeImmutable('2019-04-08');
            }
        };

        $client = new CdekClient('foo', 'bar', $this->getHttpClient('text/plain', 'example'));
        $client->sendRequest($request);

        $this->assertArrayHasKey('form_params', $this->lastRequestOptions);
        $this->assertSame('2019-04-08', $this->lastRequestOptions['form_params']['date']);
        $this->assertSame('foo', $this->lastRequestOptions['form_params']['account']);
        $this->assertSame('161313c50e420e3ba9231ec75fbac139', $this->lastRequestOptions['form_params']['secure']);
    }

    public function test_client_rejects_contract_numbers()
    {
        $this->expectException(\RuntimeException::class);

        new CdekClient('ИМ12345', 'bar');
    }
}
