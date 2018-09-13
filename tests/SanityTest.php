<?php
/*
 * This code is licensed under the MIT License.
 *
 * Copyright (c) 2018 appwilio <appwilio.com>
 * Copyright (c) 2018 JhaoDa <jhaoda@gmail.com>
 * Copyright (c) 2018 greabock <greabock17@gmail.com>
 * Copyright (c) 2018 Alexey Kopytko <alexey@kopytko.com>
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

namespace Tests\Appwilio\CdekSDK;

use Appwilio\CdekSDK\CdekClient;
use Appwilio\CdekSDK\Common\AdditionalService;
use Appwilio\CdekSDK\Common\Address;
use Appwilio\CdekSDK\Common\Attempt;
use Appwilio\CdekSDK\Common\Call;
use Appwilio\CdekSDK\Common\CallCourier;
use Appwilio\CdekSDK\Common\CallDelay;
use Appwilio\CdekSDK\Common\CallFail;
use Appwilio\CdekSDK\Common\CallGood;
use Appwilio\CdekSDK\Common\ChangePeriod;
use Appwilio\CdekSDK\Common\City;
use Appwilio\CdekSDK\Common\Item;
use Appwilio\CdekSDK\Common\OfficeImage;
use Appwilio\CdekSDK\Common\Order;
use Appwilio\CdekSDK\Common\Package;
use Appwilio\CdekSDK\Common\Pvz;
use Appwilio\CdekSDK\Common\Reason;
use Appwilio\CdekSDK\Common\State;
use Appwilio\CdekSDK\Common\Status;
use Appwilio\CdekSDK\Common\WeightLimit;
use Appwilio\CdekSDK\Contracts\Request;
use Appwilio\CdekSDK\Requests\CalculationAuthorizedRequest;
use Appwilio\CdekSDK\Requests\CalculationRequest;
use Appwilio\CdekSDK\Requests\DeleteRequest;
use Appwilio\CdekSDK\Requests\DeliveryRequest;
use Appwilio\CdekSDK\Requests\InfoReportRequest;
use Appwilio\CdekSDK\Requests\PrintReceiptsRequest;
use Appwilio\CdekSDK\Requests\PvzListRequest;
use Appwilio\CdekSDK\Requests\StatusReportRequest;
use Appwilio\CdekSDK\Responses\CalculationResponse;
use Appwilio\CdekSDK\Responses\DeleteResponse;
use Appwilio\CdekSDK\Responses\DeliveryResponse;
use Appwilio\CdekSDK\Responses\InfoReportResponse;
use Appwilio\CdekSDK\Responses\PrintErrorResponse;
use Appwilio\CdekSDK\Responses\PvzListResponse;
use Appwilio\CdekSDK\Responses\StatusReportResponse;
use Appwilio\CdekSDK\Responses\Types\Error;
use Appwilio\CdekSDK\Responses\Types\Message;
use Appwilio\CdekSDK\Responses\Types\PrintError;
use Appwilio\CdekSDK\Responses\Types\Result;
use Appwilio\CdekSDK\Serialization\NullableDateTimeHandler;
use PHPUnit\Framework\TestCase;

/**
 * @coversNothing
 */
class SanityTest extends TestCase
{
    const CLASS_NAMES = [
        CdekClient::class,
        AdditionalService::class,
        Address::class,
        Attempt::class,
        Call::class,
        CallCourier::class,
        CallDelay::class,
        CallFail::class,
        CallGood::class,
        ChangePeriod::class,
        City::class,
        Item::class,
        OfficeImage::class,
        Order::class,
        Package::class,
        Pvz::class,
        Reason::class,
        State::class,
        Status::class,
        WeightLimit::class,
        CalculationAuthorizedRequest::class,
        CalculationRequest::class,
        DeleteRequest::class,
        DeliveryRequest::class,
        InfoReportRequest::class,
        PrintReceiptsRequest::class,
        PvzListRequest::class,
        StatusReportRequest::class,
        CalculationResponse::class,
        DeleteResponse::class,
        DeliveryResponse::class,
        InfoReportResponse::class,
        PrintErrorResponse::class,
        PvzListResponse::class,
        StatusReportResponse::class,
        Error::class,
        Message::class,
        PrintError::class,
        Result::class,
        NullableDateTimeHandler::class,
    ];

    public function allClassNames(): array
    {
        return array_map(function ($className) {
            return [$className];
        }, self::CLASS_NAMES);
    }

    /**
     * @dataProvider allClassNames
     *
     * @param mixed $className
     */
    public function test_class_exist($className)
    {
        $this->assertTrue(class_exists($className));
    }

    private $legacyMaps = [
        'xml' => [
            DeleteRequest::class => DeleteResponse::class,
            PvzListRequest::class => PvzListResponse::class,
            DeliveryRequest::class => DeliveryResponse::class,
            InfoReportRequest::class => InfoReportResponse::class,
            StatusReportRequest::class => StatusReportResponse::class,
            PrintReceiptsRequest::class => PrintErrorResponse::class,
        ],
        'json' => [
            CalculationRequest::class => CalculationResponse::class,
            CalculationAuthorizedRequest::class => CalculationResponse::class,
        ],
    ];

    public function legacyMaps(): \Generator
    {
        foreach ($this->legacyMaps as $format => $mapping) {
            foreach ($mapping as $request => $response) {
                yield [$format, $request, $response];
            }
        }
    }

    /**
     * @dataProvider legacyMaps
     *
     * @param mixed $format
     * @param mixed $request
     * @param mixed $response
     */
    public function test_legacy_mapping_checks_out($format, $request, $response)
    {
        $request = new $request();
        /** @var $request Request */
        $this->assertInstanceOf(Request::class, $request);

        $this->assertSame($format, $request->getSerializationFormat());
        $this->assertSame($response, $request->getResponseClassName());
    }
}
