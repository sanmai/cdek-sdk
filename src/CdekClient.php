<?php

/**
 * This file is part of Cdek SDK package.
 *
 * © Appwilio (http://appwilio.com), greabock (https://github.com/greabock), JhaoDa (https://github.com/jhaoda)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Appwilio\CdekSDK;

use JMS\Serializer\Serializer;
use JMS\Serializer\SerializerBuilder;
use JMS\Serializer\Handler\HandlerRegistry;
use GuzzleHttp\Client as GuzzleClient;
use Appwilio\CdekSDK\Requests\CdekRequest;
use Appwilio\CdekSDK\Requests\DeleteRequest;
use Appwilio\CdekSDK\Requests\CdekXmlRequest;
use Appwilio\CdekSDK\Requests\PvzListRequest;
use Appwilio\CdekSDK\Requests\CdekJsonRequest;
use Appwilio\CdekSDK\Requests\DeliveryRequest;
use Appwilio\CdekSDK\Requests\CdekParamRequest;
use Appwilio\CdekSDK\Requests\InfoReportRequest;
use Appwilio\CdekSDK\Requests\CalculationRequest;
use Appwilio\CdekSDK\Responses\CalculationResponse;
use Appwilio\CdekSDK\Responses\DeleteResponse;
use Appwilio\CdekSDK\Responses\PvzListResponse;
use Appwilio\CdekSDK\Responses\DeliveryResponse;
use Appwilio\CdekSDK\Responses\InfoReportResponse;
use Appwilio\CdekSDK\Requests\StatusReportRequest;
use Appwilio\CdekSDK\Responses\StatusReportResponse;
use Appwilio\CdekSDK\Serialization\NullableDateTimeHandler;

class CdekClient
{
    protected $maps = [
        'xml'  => [
            DeleteRequest::class       => DeleteResponse::class,
            PvzListRequest::class      => PvzListResponse::class,
            DeliveryRequest::class     => DeliveryResponse::class,
            InfoReportRequest::class   => InfoReportResponse::class,
            StatusReportRequest::class => StatusReportResponse::class,
        ],
        'json' => [
            CalculationRequest::class => CalculationResponse::class,
        ],
    ];

    /** @var GuzzleClient */
    private $http;

    /** @var string */
    private $account;

    /** @var string */
    private $password;

    /** @var Serializer */
    private $serializer;

    public function __construct(string $account, string $password)
    {
        $this->account = $account;
        $this->password = $password;

        $this->http = new GuzzleClient();

        $this->serializer = SerializerBuilder::create()->configureHandlers(function (HandlerRegistry $registry) {
            $registry->registerSubscribingHandler(new NullableDateTimeHandler());
        })->build();
    }

    protected function sendRequest(CdekRequest $request): string
    {
        $this->initRequest($request);

        $response = $this->http->request(
            $request->getMethod(),
            $request->getAddress(),
            $this->extractOptions($request)
        );

        return $response->getBody()->getContents();
    }

    public function sendPvzListRequest(PvzListRequest $request): PvzListResponse
    {
        return $this->process($request);
    }

    public function sendStatusReportRequest(StatusReportRequest $request): StatusReportResponse
    {
        return $this->process($request);
    }

    public function sendCalculationRequest(CalculationRequest $request): CalculationResponse
    {
        return $this->process($request);
    }

    private function extractOptions($request): array
    {
        if ($request instanceof CdekParamRequest) {
            return ['form_params' => $request->getParams()];
        }

        if ($request instanceof CdekXmlRequest) {
            return ['form_params' => ['xml_request' => $this->serializer->serialize($request, 'xml')]];
        }

        if ($request instanceof CdekJsonRequest) {
            return [
                'body'    => json_encode($request->getBody()),
                'headers' => ['Content-Type' => 'application/json'],
            ];
        }

        return [];
    }

    public function sendDeliveryRequest(DeliveryRequest $request): DeliveryResponse
    {
        return $this->process($request);
    }

    public function sendInfoReportRequest(InfoReportRequest $request): InfoReportResponse
    {
        return $this->process($request);
    }

    public function sendDeleteRequest($request): DeleteResponse
    {
        return $this->process($request);
    }

    public function process($request)
    {
        $class = get_class($request);

        $response = $this->sendRequest($request);

        foreach ($this->maps as $dataType => $map) {
            if (array_key_exists($class, $map)) {
                return $this->serializer->deserialize($response, $map[$class], $dataType);
            }
        }

        throw new \Exception("Class [$class] not mapped.");
    }

    private function initRequest(CdekRequest $request): void
    {
        $date = new \DateTimeImmutable();

        $request->date($date)->credentials($this->account, $this->getSecure($date));
    }

    private function getSecure(\DateTimeInterface $date): string
    {
        return md5($date->format('Y-m-d')."&{$this->password}");
    }
}
