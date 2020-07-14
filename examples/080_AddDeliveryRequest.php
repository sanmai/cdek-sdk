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
use CdekSDK\Common;
use CdekSDK\Requests;

$client = new \CdekSDK\CdekClient('account', 'password');

$order = new Common\Order([
    'Number'           => 'TEST-123456',
    'SendCityCode'     => 44, // Москва
    'RecCityPostCode'  => '630001', // Новосибирск
    'RecipientName'    => 'Иван Петров',
    'RecipientEmail'   => 'petrov@test.ru',
    'Phone'            => '+7 (383) 202-22-50',
    'TariffTypeCode'   => 1,
    'RecipientCompany' => 'Петров и партнёры, ООО',
    'Comment'          => 'Это тестовый заказ',
]);

$order->setSender(Common\Sender::create([
    'Company' => 'ЗАО «Рога и Копыта»',
    'Name'    => 'Петр Иванов',
    'Phone'   => '+7 (283) 101-11-20',
])->setAddress(Common\Address::create([
    'Street' => 'Морозильная улица',
    'House'  => '2',
    'Flat'   => '101',
])));

$order->setAddress(Common\Address::create([
    'Street'  => 'Холодильная улица',
    'House'   => '16',
    'Flat'    => '22',
]));

$package = Common\Package::create([
    'Number'  => 'TEST-123456',
    'BarCode' => 'TEST-123456',
    'Weight'  => 500, // Общий вес (в граммах)
    'SizeA'   => 10, // Длина (в сантиметрах), в пределах от 1 до 1500
    'SizeB'   => 10,
    'SizeC'   => 10,
    'Comment' => 'Обязательное описание вложения',
]);

$order->addPackage($package);

$order->addService(Common\AdditionalService::create(Common\AdditionalService::SERVICE_DELIVERY_TO_DOOR));

$request = new Requests\AddDeliveryRequest([
    'Number'          => 'TESTING123',
    'ForeignDelivery' => false,
    'Currency'        => 'RUB',
]);
$request->addOrder($order);

$response = $client->sendAddDeliveryRequest($request);

if ($response->hasErrors()) {
    // обработка ошибок
}

foreach ($response->getOrders() as $order) {
    // сверяем данные заказа, записываем номер
    $order->getNumber();
    $order->getDispatchNumber();
}
