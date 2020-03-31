<?php

declare(strict_types=1);
use CdekSDK\Requests;

const TARIFF_INTERNATIONAL_EXPRESS_DOOR_DOOR = 8;

$client = new \CdekSDK\CdekClient('account', 'password');

// you can use
// $request = new Requests\CalculationWithTariffListRequest();
// to send Calculation request without authorization

$request = new Requests\CalculationWithTariffListAuthorizedRequest();
$request->setSenderCityPostCode('90248')
    ->setSenderCity('Gardena')
    ->setSenderCountryCode('US')
    ->setReceiverCityPostCode('101000')
    ->setCurrency('USD')
    ->addTariffToList(TARIFF_INTERNATIONAL_EXPRESS_DOOR_DOOR)
    ->addPackage([
        'weight' => 0.2,
        'length' => 25,
        'width'  => 15,
        'height' => 10,
    ]);

$response = $client->sendCalculationWithTariffListRequest($request);

/** @var \CdekSDK\Responses\CalculationWithTariffListResponse $response */
if ($response->hasErrors()) {
    foreach ($response->getErrors() as $err) {
        echo $err->getErrorCode() . "\n";
        echo $err->getMessage() . "\n";
    }
}

foreach ($response->getResults() as $result) {
    if ($result->hasErrors()) {
        // обработка ошибок

        continue;
    }

    if (!$result->getStatus()) {
        continue;
    }

    echo 'Tariff ID: ' . $result->getTariffId() . "\n";
    // int(1)

    echo 'price: ' . $result->getPrice(). "\n";
    // double(1570)

    echo 'currency: ' . $result->getCurrency() . "\n";

    echo 'Delivery Period Min: ' . $result->getDeliveryPeriodMin() . "\n";
    // int(4)

    echo 'Delivery Perion Max: ' . $result->getDeliveryPeriodMax() . "\n";
    // int(5)
}
