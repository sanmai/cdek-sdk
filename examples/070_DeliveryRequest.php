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
    'Number'          => 'TEST-123456',
    'SendCityCode'    => 44, // Москва
    'RecCityPostCode' => '630001', // Новосибирск
    'RecipientName'   => 'Иван Петров',
    'RecipientEmail'  => 'petrov@test.ru',
    'Phone'           => '+7 (383) 202-22-50',
    'TariffTypeCode'  => 139, // Посылка дверь-дверь от ИМ
]);

$order->setAddress(Common\Address::create([
    'Street' => 'Холодильная улица',
    'House'  => '16',
    'Flat'   => '22',
]));

$package = Common\Package::create([
    'Number'  => 'TEST-123456',
    'BarCode' => 'TEST-123456',
    'Weight'  => 500, // Общий вес (в граммах)
    'SizeA'   => 10, // Длина (в сантиметрах), в пределах от 1 до 1500
    'SizeB'   => 10,
    'SizeC'   => 10,
]);

$package->addItem(new Common\Item([
    'WareKey' => 'NN0001', // Идентификатор/артикул товара/вложения
    'Cost'    => 500, // Объявленная стоимость товара (за единицу товара)
    'Payment' => 0, // Оплата за товар при получении (за единицу товара)
    'Weight'  => 120, // Вес (за единицу товара, в граммах)
    'Amount'  => 2, // Количество единиц одноименного товара (в штуках)
    'Comment' => 'Test item',
]));

$order->addPackage($package);

$request = new Requests\DeliveryRequest([
    'Number' => 'TESTING123',
]);
$request->addOrder($order);

$response = $client->sendDeliveryRequest($request);

if ($response->hasErrors()) {
    // обработка ошибок

    foreach ($response->getErrors() as $order) {
        // заказы с ошибками
        $order->getMessage();
        $order->getErrorCode();
        $order->getNumber();
    }

    foreach ($response->getMessages() as $message) {
        // Сообщения об ошибках
    }
}

foreach ($response->getOrders() as $order) {
    // сверяем данные заказа, записываем номер
    $order->getNumber();
    $order->getDispatchNumber();
    break;
}
