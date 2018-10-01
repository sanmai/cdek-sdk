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
use CdekSDK\Common\AdditionalService;
use CdekSDK\Common\Address;
use CdekSDK\Common\Attempt;
use CdekSDK\Common\Call;
use CdekSDK\Common\CallCourier;
use CdekSDK\Common\CallDelay;
use CdekSDK\Common\CallFail;
use CdekSDK\Common\CallGood;
use CdekSDK\Common\ChangePeriod;
use CdekSDK\Common\City;
use CdekSDK\Common\Item;
use CdekSDK\Common\OfficeImage;
use CdekSDK\Common\Order;
use CdekSDK\Common\Package;
use CdekSDK\Common\Pvz;
use CdekSDK\Common\Reason;
use CdekSDK\Common\State;
use CdekSDK\Common\Status;
use CdekSDK\Common\WeightLimit;
use CdekSDK\Contracts\Request;
use CdekSDK\Requests\CalculationAuthorizedRequest;
use CdekSDK\Requests\CalculationRequest;
use CdekSDK\Requests\DeleteRequest;
use CdekSDK\Requests\DeliveryRequest;
use CdekSDK\Requests\InfoReportRequest;
use CdekSDK\Requests\PrintReceiptsRequest;
use CdekSDK\Requests\PvzListRequest;
use CdekSDK\Requests\StatusReportRequest;
use CdekSDK\Responses\CalculationResponse;
use CdekSDK\Responses\DeleteResponse;
use CdekSDK\Responses\DeliveryResponse;
use CdekSDK\Responses\InfoReportResponse;
use CdekSDK\Responses\PrintErrorResponse;
use CdekSDK\Responses\PvzListResponse;
use CdekSDK\Responses\StatusReportResponse;
use CdekSDK\Responses\Types\Error;
use CdekSDK\Responses\Types\Message;
use CdekSDK\Responses\Types\PrintError;
use CdekSDK\Responses\Types\Result;
use CdekSDK\Serialization\NullableDateTimeHandler;
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
            DeleteRequest::class        => DeleteResponse::class,
            PvzListRequest::class       => PvzListResponse::class,
            DeliveryRequest::class      => DeliveryResponse::class,
            InfoReportRequest::class    => InfoReportResponse::class,
            StatusReportRequest::class  => StatusReportResponse::class,
            PrintReceiptsRequest::class => PrintErrorResponse::class,
        ],
        'json' => [
            CalculationRequest::class           => CalculationResponse::class,
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
