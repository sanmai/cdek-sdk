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
use CdekSDK\Contracts\Response;
use CdekSDK\Contracts\ShouldAuthorize;
use CdekSDK\Contracts\XmlRequest;
use CdekSDK\Responses\ErrorResponse;
use CdekSDK\Responses\FileResponse;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\ServerException;
use JMS\Serializer\SerializerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use function GuzzleHttp\default_user_agent;

/**
 * Class CdekClient.
 *
 * @method Responses\DeleteResponse                            sendDeleteRequest(Requests\DeleteRequest $request)
 * @method Responses\PvzListResponse                           sendPvzListRequest(Requests\PvzListRequest $request)
 * @method Responses\DeliveryResponse                          sendDeliveryRequest(Requests\DeliveryRequest $request)
 * @method Responses\CallCourierResponse                       sendCallCourierRequest(Requests\CallCourierRequest $request)
 * @method Responses\ScheduleResponse                          sendScheduleRequest(Requests\ScheduleRequest $request)
 * @method Responses\PreAlertResponse                          sendPreAlertRequest(Requests\PreAlertRequest $request, \DateTimeInterface $plannedDate = null)
 * @method Responses\InfoReportResponse                        sendInfoReportRequest(Requests\InfoReportRequest $request)
 * @method Responses\CalculationResponse                       sendCalculationRequest(Requests\CalculationRequest $request)
 * @method Responses\StatusReportResponse                      sendStatusReportRequest(Requests\StatusReportRequest $request)
 * @method Responses\FileResponse|Responses\PrintErrorResponse sendPrintReceiptsRequest(Requests\PrintReceiptsRequest $request)
 * @method Responses\FileResponse|Responses\PrintErrorResponse sendPrintLabelsRequest(Requests\PrintLabelsRequest $request)
 * @method Responses\RegionsResponse                           sendRegionsRequest(Requests\RegionsRequest $request)
 * @method Responses\CitiesResponse                            sendCitiesRequest(Requests\CitiesRequest $request)
 */
final class CdekClient implements Contracts\Client, LoggerAwareInterface
{
    use LoggerAwareTrait;

    const STANDARD_BASE_URL = 'https://integration.cdek.ru';

    const DEFAULT_TIMEOUT = 60;

    const PACKAGE_NAME = 'Cdek-SDK';
    const VERSION_INFO = '$Format:%h%d by %an +%ae$';

    /** @var ClientInterface */
    private $http;

    /** @var string */
    private $account;

    /** @var string */
    private $password;

    /** @var SerializerInterface */
    private $serializer;

    public function __construct(string $account = '', string $password = '', ClientInterface $http = null)
    {
        $this->account = $account;
        $this->password = $password;

        $this->http = $http ?? new GuzzleClient([
            'base_uri' => self::STANDARD_BASE_URL,
            'timeout'  => self::DEFAULT_TIMEOUT,
            'headers'  => [
                'User-Agent' => $this->getDefaultUserAgent(),
            ],
        ]);

        $this->serializer = new Serialization\Serializer();
    }

    /**
     * @codeCoverageIgnore
     */
    private function getDefaultUserAgent(): string
    {
        if (self::VERSION_INFO[0] === '$') {
            return default_user_agent().' '.self::PACKAGE_NAME.'/'.@json_decode((string) file_get_contents(__DIR__.'/../composer.json'), true)['extra']['branch-alias']['dev-master'];
        }

        return default_user_agent().' '.self::PACKAGE_NAME.'/'.self::VERSION_INFO;
    }

    /**
     * {@inheritdoc}
     *
     * @see \CdekSDK\Contracts\Client::sendRequest()
     *
     * @return Response
     */
    public function sendRequest(Request $request, \DateTimeInterface $requestDate = null)
    {
        if ($request instanceof ShouldAuthorize) {
            $requestDate = $requestDate ?? new \DateTimeImmutable();

            $request->date($requestDate)->credentials($this->account, $this->getSecure($requestDate));
        }

        try {
            $response = $this->http->request(
                $request->getMethod(),
                $request->getAddress(),
                $this->extractOptions($request)
            );
        } catch (ServerException $exception) {
            // Новые методы СДЭК дают 500 ошибку, вместе с тем отдавая XML с ошибкой
            if (!$response = $exception->getResponse()) {
                // В случае ошибок без содержимого кидаем исключение дальше
                throw $exception;
            }

            if ($this->logger) {
                $this->logger->debug('CDEK API responded with an HTTP error code {error_code}', [
                    'exception'  => $exception,
                    'error_code' => $exception->getCode(),
                ]);
            }
        }

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

            return ErrorResponse::withHTTPResponse($response);
        }

        $responseBody = (string) $response->getBody();

        if ($this->logger) {
            $this->logger->debug($responseBody);
        }

        return $this->serializer->deserialize($responseBody, $request->getResponseClassName(), $request->getSerializationFormat());
    }

    private function serialize(XmlRequest $request): string
    {
        try {
            $requestBody = $this->serializer->serialize($request, XmlRequest::SERIALIZATION_XML);

            if ($this->logger) {
                $this->logger->debug($requestBody);
            }

            return $requestBody;
            // @codeCoverageIgnoreStart
        } catch (\Doctrine\Common\Annotations\AnnotationException $e) {
            throw new \RuntimeException('Serialization failed. Have you forgotten to initialize an autoloader for AnnotationRegistry?', 0, $e);
        }
        // @codeCoverageIgnoreEnd
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

        return strpos($response->getHeader('Content-Disposition')[0], 'attachment') === 0;
    }

    private function isTextResponse(ResponseInterface $response): bool
    {
        $header = $response->hasHeader('Content-Type')
            ? $response->getHeader('Content-Type')[0]
            : '';

        // Разбиваем условие на части чтобы лучше видеть покрытие тестами
        if (0 === strpos($header, 'text/xml')) {
            return true;
        }

        if (0 === strpos($header, 'application/xml')) {
            return true;
        }

        if (0 === strpos($header, 'application/json')) {
            return true;
        }

        return false;
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
                    'xml_request' => $this->serialize($request),
                ],
            ];
        }

        if ($request instanceof JsonRequest) {
            return [
                'body'    => json_encode($request->getBody()),
                'headers' => [
                    'Content-Type' => 'application/json',
                ],
            ];
        }

        return [];
    }
}
