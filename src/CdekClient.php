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

use CdekSDK\Contracts\DateAware;
use CdekSDK\Contracts\JsonRequest;
use CdekSDK\Contracts\ParamRequest;
use CdekSDK\Contracts\Request;
use CdekSDK\Contracts\Response;
use CdekSDK\Contracts\ShouldAuthorize;
use CdekSDK\Contracts\XmlRequest;
use CdekSDK\Responses\ErrorResponse;
use CdekSDK\Responses\FileResponse;
use CdekSDK\Responses\JsonErrorResponse;
use CdekSDK\Serialization\Exception\XmlErrorException;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\ClientInterface;
use function GuzzleHttp\default_user_agent;
use GuzzleHttp\Exception\BadResponseException;
use JMS\Serializer\SerializerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;

/**
 * Class CdekClient.
 *
 * @deprecated https://github.com/cdek-it/sdk2.0
 *
 * @method Responses\DeleteResponse                      sendDeleteRequest(Requests\DeleteRequest $request)
 * @method Responses\PvzListResponse|Common\Pvz[]        sendPvzListRequest(Requests\PvzListRequest $request)
 * @method Responses\DeliveryResponse                    sendDeliveryRequest(Requests\DeliveryRequest $request)
 * @method Responses\DeliveryResponse                    sendAddDeliveryRequest(Requests\AddDeliveryRequest $request)
 * @method Responses\UpdateResponse                      sendUpdateRequest(Requests\UpdateRequest $request)
 * @method Responses\CallCourierResponse                 sendCallCourierRequest(Requests\CallCourierRequest $request)
 * @method Responses\ScheduleResponse                    sendScheduleRequest(Requests\ScheduleRequest $request)
 * @method Responses\InfoReportResponse|Common\Order[]   sendInfoReportRequest(Requests\InfoReportRequest $request)
 * @method Responses\CalculationResponse                 sendCalculationRequest(Requests\CalculationAuthorizedRequest $request)
 * @method Responses\CalculationWithTariffListResponse   sendCalculationWithTariffListRequest(Requests\CalculationWithTariffListAuthorizedRequest $request)
 * @method Responses\StatusReportResponse|Common\Order[] sendStatusReportRequest(Requests\StatusReportRequest $request)
 * @method Responses\FileResponse                        sendPrintReceiptsRequest(Requests\PrintReceiptsRequest $request)
 * @method Responses\FileResponse                        sendPrintLabelsRequest(Requests\PrintLabelsRequest $request)
 * @method Responses\RegionsResponse|Common\Region[]     sendRegionsRequest(Requests\RegionsRequest $request)
 * @method Responses\CitiesResponse|Common\Location[]    sendCitiesRequest(Requests\CitiesRequest $request)
 */
final class CdekClient implements Contracts\Client, LoggerAwareInterface
{
    use LoggerAwareTrait;

    const STANDARD_BASE_URL = 'https://integration.cdek.ru';

    const DEFAULT_TIMEOUT = 60;

    const PACKAGE_NAME = 'Cdek-SDK';
    const VERSION_INFO = '$Format:%h%d by %an +%ae$';

    /**
     * Формат даты, который используется для создания подписи запроса.
     *
     * @var string
     */
    const SECURE_DATE_FORMAT = 'Y-m-d\TH:i:s';

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
        if (\strpos($account, 'ИМ') === 0) {
            throw new \RuntimeException('Учетная запись для интеграции не совпадает с учетной записью доступа в Личный кабинет СДЭК.');
        }

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

    private static $userAgentPostfix;

    public static function setUserAgent(string $product, string $versionDetails)
    {
        self::$userAgentPostfix = \sprintf('%s/%s', $product, $versionDetails);
    }

    /**
     * @codeCoverageIgnore
     *
     * @phan-suppress PhanDeprecatedFunction
     * @psalm-suppress DeprecatedFunction
     */
    private function getDefaultUserAgent(): string
    {
        if (self::$userAgentPostfix === null) {
            self::setUserAgent(self::PACKAGE_NAME, self::getVersion());
        }

        \assert(\is_string(self::$userAgentPostfix));

        return default_user_agent().' '.self::$userAgentPostfix;
    }

    /**
     * @codeCoverageIgnore
     * @psalm-suppress MixedArrayAccess
     */
    private static function getVersion(): string
    {
        if (self::VERSION_INFO[0] === '$' && \is_dir(__DIR__.'/../.git')) {
            return (string) \exec(\sprintf('git --git-dir=%s describe --tags --dirty=-dev --always', \escapeshellarg(__DIR__.'/../.git')));
        }

        if (\preg_match('/^([0-9a-f]+).*?tag: (v?[\d\.]+)\)(.*)/', self::VERSION_INFO, $parts)) {
            /** @var string[] $parts */
            return "{$parts[2]}-{$parts[1]}{$parts[3]}";
        }

        /** @phan-suppress-next-line PhanTypeArraySuspiciousNullable */
        return (string) @\json_decode((string) \file_get_contents(__DIR__.'/../composer.json'), true)['extra']['branch-alias']['dev-master'];
    }

    /**
     * @see \CdekSDK\Contracts\Client::sendRequest()
     * @deprecated $requestDate is deprecated and will be removed; use DateAware interface instead
     *
     * @return Response
     */
    public function sendRequest(Request $request, \DateTimeInterface $requestDate = null)
    {
        if ($request instanceof DateAware) {
            $requestDate = $request->getRequestDate();
        }

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
        } catch (BadResponseException $exception) {
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

    /** @phan-suppress PhanDeprecatedFunction */
    public function __call(string $name, array $arguments)
    {
        if (0 === \strpos($name, 'send')) {
            /** @psalm-suppress MixedArgument */
            return $this->sendRequest(...$arguments);
        }

        throw new \BadMethodCallException(\sprintf('Method [%s] not found in [%s].', $name, __CLASS__));
    }

    /**
     * @psalm-suppress InvalidReturnStatement
     * @psalm-suppress InvalidReturnType
     * @psalm-suppress LessSpecificReturnStatement
     * @psalm-suppress ArgumentTypeCoercion
     * @psalm-suppress MoreSpecificReturnType
     */
    private function deserialize(Request $request, ResponseInterface $response): Response
    {
        $contentType = $this->getContentTypeHeader($response);

        if ($this->logger) {
            $this->logger->debug('Content-Type: {content-type}', [
                'content-type' => $contentType,
            ]);
        }

        if (!$this->isTextResponse($contentType)) {
            if ($this->hasAttachment($response)) {
                return new FileResponse($response->getBody());
            }

            return ErrorResponse::withHTTPResponse($response);
        }

        $responseBody = (string) $response->getBody();

        if ($this->logger) {
            $this->logger->debug($responseBody);
        }

        try {
            return $this->serializer->deserialize($responseBody, $request->getResponseClassName(), $request->getSerializationFormat());
        } catch (XmlErrorException $xmlException) {
            /*
             * Ответ может быть пустой строкой
             */
            if ($responseBody === '') {
                return ErrorResponse::withHTTPResponse($response);
            }

            /*
             * Вместо XML СДЭК может вернуть JSON с описанием ошибки, характерно - с кодом 503.
             */
            if (\substr($responseBody, 0, 1) !== '{') {
                // Это не JSON, с которым мы что-то можем сделать: потому кидаем исключение
                throw $xmlException;
            }

            try {
                return $this->serializer->deserialize($responseBody, JsonErrorResponse::class, Request::SERIALIZATION_JSON);
            } catch (\JMS\Serializer\Exception\RuntimeException $_) {
                /*
                 * Если неудалось разобрать JSON, чтобы не вносить путаницу какими-то новыми исключениями,
                 * кинем предыдущее исключение про ошибку разбора XML.
                 */
                throw $xmlException;
            }
        }
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

    /**
     * @see \CdekSDK\Requests\Concerns\Authorized::$date
     */
    private function getSecure(\DateTimeInterface $date): string
    {
        return \md5($date->format(self::SECURE_DATE_FORMAT)."&{$this->password}");
    }

    private function hasAttachment(ResponseInterface $response): bool
    {
        if (!$response->hasHeader('Content-Disposition')) {
            return false;
        }

        return \strpos($response->getHeader('Content-Disposition')[0], 'attachment') === 0;
    }

    private function getContentTypeHeader(ResponseInterface $response): string
    {
        if ($response->hasHeader('Content-Type')) {
            return $response->getHeader('Content-Type')[0];
        }

        return '';
    }

    private function isTextResponse(string $header): bool
    {
        // Разбиваем условие на части чтобы лучше видеть покрытие тестами
        if (0 === \strpos($header, 'text/xml')) {
            return true;
        }

        if (0 === \strpos($header, 'application/xml')) {
            return true;
        }

        if (0 === \strpos($header, 'application/atom+xml')) {
            return true;
        }

        if (0 === \strpos($header, 'application/json')) {
            return true;
        }

        return false;
    }

    private function extractOptions(Request $request): array
    {
        if ($this->logger) {
            $this->logger->debug('{method} {location}', [
                'method'   => $request->getMethod(),
                'location' => $request->getAddress(),
            ]);
        }

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
            $requestBody = \json_encode($request);

            if ($this->logger) {
                $this->logger->debug($requestBody);
            }

            return [
                'body'    => $requestBody,
                'headers' => [
                    'Content-Type' => 'application/json',
                ],
            ];
        }

        return [];
    }
}
