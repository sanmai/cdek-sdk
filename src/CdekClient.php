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

use CdekSDK\Contracts\JsonRequest;
use CdekSDK\Contracts\ParamRequest;
use CdekSDK\Contracts\Request;
use CdekSDK\Contracts\ShouldAuthorize;
use CdekSDK\Contracts\XmlRequest;
use CdekSDK\Responses\FileResponse;
use CdekSDK\Serialization\NullableDateTimeHandler;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\ClientInterface;
use JMS\Serializer\Handler\HandlerRegistry;
use JMS\Serializer\Serializer;
use JMS\Serializer\SerializerBuilder;
use Psr\Http\Message\ResponseInterface;

/**
 * Class CdekClient.
 *
 * @method Responses\DeleteResponse                            sendDeleteRequest(Requests\DeleteRequest $request)
 * @method Responses\PvzListResponse                           sendPvzListRequest(Requests\PvzListRequest $request)
 * @method Responses\DeliveryResponse                          sendDeliveryRequest(Requests\DeliveryRequest $request)
 * @method Responses\InfoReportResponse                        sendInfoReportRequest(Requests\InfoReportRequest $request)
 * @method Responses\CalculationResponse                       sendCalculationRequest(Requests\CalculationRequest $request)
 * @method Responses\StatusReportResponse                      sendStatusReportRequest(Requests\StatusReportRequest $request)
 * @method Responses\FileResponse|Responses\PrintErrorResponse sendPrintReceiptsRequest(Requests\PrintReceiptsRequest $request)
 * @method Responses\FileResponse|Responses\PrintErrorResponse sendPrintLabelsRequest(Requests\PrintLabelsRequest $request)
 */
final class CdekClient
{
    const STANDARD_BASE_URL = 'https://integration.cdek.ru';

    /** @var ClientInterface */
    private $http;

    /** @var string */
    private $account;

    /** @var string */
    private $password;

    /** @var Serializer */
    private $serializer;

    public function __construct(string $account, string $password, ClientInterface $http = null)
    {
        $this->account = $account;
        $this->password = $password;

        $this->http = $http ?? new GuzzleClient([
            'base_uri' => self::STANDARD_BASE_URL,
        ]);

        $this->serializer = SerializerBuilder::create()->configureHandlers(function (HandlerRegistry $registry) {
            $registry->registerSubscribingHandler(new NullableDateTimeHandler());
        })->build();

        // Ignore Phan issue-suppressing annotations
        \Doctrine\Common\Annotations\AnnotationReader::addGlobalIgnoredName('phan');
    }

    public function sendRequest(Request $request)
    {
        if ($request instanceof ShouldAuthorize) {
            $date = new \DateTimeImmutable();

            $request->date($date)->credentials($this->account, $this->getSecure($date));
        }

        $response = $this->http->request(
            $request->getMethod(),
            $request->getAddress(),
            $this->extractOptions($request)
        );

        return $this->deserialize($request, $response);
    }

    public function __call($name, $arguments)
    {
        if (0 === strpos($name, 'send')) {
            return $this->sendRequest(...$arguments);
        }

        throw new \BadMethodCallException(sprintf('Method [%s] not found in [%s].', $name, __CLASS__));
    }

    private function deserialize(Request $request, ResponseInterface $response)
    {
        if (!$this->isTextResponse($response)) {
            if ($this->hasAttachment($response)) {
                return new FileResponse($response->getBody());
            }

            return $response;
        }

        return $this->serializer->deserialize((string) $response->getBody(), $request->getResponseClassName(), $request->getSerializationFormat());
    }

    private function getSecure(\DateTimeInterface $date): string
    {
        return md5($date->format('Y-m-d')."&{$this->password}");
    }

    private function hasAttachment(ResponseInterface $response): bool
    {
        if (!$response->hasHeader('Content-Disposition')) {
            return false;
        }

        return strpos($response->getHeader('Content-Disposition')[0], 'attachment') !== false;
    }

    private function isTextResponse(ResponseInterface $response): bool
    {
        $header = $response->hasHeader('Content-Type')
            ? $response->getHeader('Content-Type')[0]
            : '';

        return 0 === strpos($header, 'text/xml') || 0 === strpos($header, 'application/json');
    }

    private function extractOptions(Request $request): array
    {
        if ($request instanceof ParamRequest) {
            if ($request->getMethod() === 'GET') {
                return [
                    'query' => $request->getParams(),
                ];
            }

            return [
                'form_params' => $request->getParams(),
            ];
        }

        if ($request instanceof XmlRequest) {
            return [
                'form_params' => [
                    'xml_request' => $this->serializer->serialize($request, 'xml'),
                ],
            ];
        }

        if ($request instanceof JsonRequest) {
            return [
                'body' => json_encode($request->getBody()),
                'headers' => [
                    'Content-Type' => 'application/json',
                ],
            ];
        }

        return [];
    }
}
