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
use CdekSDK\Requests;

$client = new \CdekSDK\CdekClient('account', 'password');

// для выполнения запроса без авторизации используется
// $request = new Requests\CalculationWithTariffListRequest();
// $request->set...() и так далее

$request = new Requests\CalculationWithTariffListAuthorizedRequest();
$request->setSenderCityPostCode('295000')
    ->setReceiverCityPostCode('652632')
    ->addTariffToList(1)
    ->addTariffToList(8)
    ->addPackage([
        'weight' => 0.2,
        'length' => 25,
        'width'  => 15,
        'height' => 10,
    ]);

$response = $client->sendCalculationWithTariffListRequest($request);

/** @var \CdekSDK\Responses\CalculationWithTariffListResponse $response */
if ($response->hasErrors()) {
    // обработка ошибок
}

foreach ($response->getResults() as $result) {
    if ($result->hasErrors()) {
        // обработка ошибок

        continue;
    }

    if (!$result->getStatus()) {
        continue;
    }

    $result->getTariffId();
    // int(1)

    $result->getPrice();
    // double(1570)

    $result->getDeliveryPeriodMin();
    // int(4)

    $result->getDeliveryPeriodMax();
    // int(5)
}
