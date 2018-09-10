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
use Appwilio\CdekSDK\Responses\PrintReceiptsResponse;
use Appwilio\CdekSDK\Responses\PvzListResponse;
use Appwilio\CdekSDK\Responses\StatusReportResponse;
use Appwilio\CdekSDK\Responses\Types\Error;
use Appwilio\CdekSDK\Responses\Types\Message;
use Appwilio\CdekSDK\Responses\Types\PrintReceiptsError;
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
        PrintReceiptsResponse::class,
        PvzListResponse::class,
        StatusReportResponse::class,
        Error::class,
        Message::class,
        PrintReceiptsError::class,
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
}
