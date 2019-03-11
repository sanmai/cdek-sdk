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

$request = new Requests\CitiesRequest();
$request->setPage(0)->setSize(10)->setRegionCode(50);

$response = $client->sendCitiesRequest($request);

if ($response->hasErrors()) {
    // обработка ошибок
}

foreach ($response as $location) {
    /** @var \CdekSDK\Common\Location $location */
    $location->getCityName();
    $location->getCityCode();
    $location->getCityUuid();
    $location->getCountry();
    $location->getCountryCodeISO();
    $location->getRegion();
    $location->getRegionCode();
    $location->getRegionCodeExt();
    $location->getSubRegion();
    $location->getPaymentLimit();
    $location->getLatitude();
    $location->getLongitude();
    $location->getKladr();
    $location->getFiasGuid();
}
