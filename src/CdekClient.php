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

namespace Appwilio\CdekSDK;

use Appwilio\CdekSDK\Contracts\JsonRequest;
use Appwilio\CdekSDK\Contracts\ParamRequest;
use Appwilio\CdekSDK\Contracts\Request;
use Appwilio\CdekSDK\Contracts\ShouldAuthorize;
use Appwilio\CdekSDK\Contracts\XmlRequest;
use Appwilio\CdekSDK\Serialization\NullableDateTimeHandler;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\ClientInterface;
use JMS\Serializer\Handler\HandlerRegistry;
use JMS\Serializer\Serializer;
use JMS\Serializer\SerializerBuilder;
use Psr\Http\Message\ResponseInterface;

/**
 * Class CdekClient.
 *
 * @method Responses\DeleteResponse       sendDeleteRequest(Requests\DeleteRequest $request)
 * @method Responses\PvzListResponse      sendPvzListRequest(Requests\PvzListRequest $request)
 * @method Responses\DeliveryResponse     sendDeliveryRequest(Requests\DeliveryRequest $request)
 * @method Responses\InfoReportResponse   sendInfoReportRequest(Requests\InfoReportRequest $request)
 * @method Responses\CalculationResponse  sendCalculationRequest(Requests\CalculationRequest $request)
 * @method Responses\StatusReportResponse sendStatusReportRequest(Requests\StatusReportRequest $request)
 * @method ResponseInterface              sendPrintReceiptsRequest(Requests\PrintReceiptsRequest $request)
 */
final class CdekClient
{
    private $maps = [
        'xml' => [
            Requests\DeleteRequest::class => Responses\DeleteResponse::class,
            Requests\PvzListRequest::class => Responses\PvzListResponse::class,
            Requests\DeliveryRequest::class => Responses\DeliveryResponse::class,
            Requests\InfoReportRequest::class => Responses\InfoReportResponse::class,
            Requests\StatusReportRequest::class => Responses\StatusReportResponse::class,
            Requests\PrintReceiptsRequest::class => Responses\PrintReceiptsResponse::class,
        ],
        'json' => [
            Requests\CalculationRequest::class => Responses\CalculationResponse::class,
            Requests\CalculationAuthorizedRequest::class => Responses\CalculationResponse::class,
        ],
    ];

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

        $this->http = $http ?? new GuzzleClient();

        $this->serializer = SerializerBuilder::create()->configureHandlers(function (HandlerRegistry $registry) {
            $registry->registerSubscribingHandler(new NullableDateTimeHandler());
        })->build();
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
            return $response;
        }

        $class = \get_class($request);

        foreach ($this->maps as $format => $map) {
            if (array_key_exists($class, $map)) {
                return $this->serializer->deserialize((string) $response->getBody(), $map[$class], $format);
            }
        }

        throw new \Exception("Class [$class] not mapped.");
    }

    private function getSecure(\DateTimeInterface $date): string
    {
        return md5($date->format('Y-m-d')."&{$this->password}");
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
