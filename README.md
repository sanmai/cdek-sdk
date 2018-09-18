# PHP SDK для API СДЭК

[![Build Status](https://travis-ci.org/sanmai/cdek-sdk.svg?branch=master)](https://travis-ci.org/sanmai/cdek-sdk)
[![Coverage Status](https://coveralls.io/repos/github/sanmai/cdek-sdk/badge.svg?branch=master)](https://coveralls.io/github/sanmai/cdek-sdk?branch=master)

Это будет самое полное SDK для [интеграции с программным комплексом СДЭК](https://www.cdek.ru/clients/integrator.html).

Возможности:

- [x] [расчёт стоимости доставки](#%D0%A0%D0%B0%D1%81%D1%87%D1%91%D1%82-%D1%81%D1%82%D0%BE%D0%B8%D0%BC%D0%BE%D1%81%D1%82%D0%B8-%D0%B4%D0%BE%D1%81%D1%82%D0%B0%D0%B2%D0%BA%D0%B8) :unlock:
- [x] получение списка пунктов выдачи заказов (ПВЗ) с фильтрацией :unlock:
- [ ] управление заказами
  - [x] [формирование новых заказов](#%D0%A0%D0%B5%D0%B3%D0%B8%D1%81%D1%82%D1%80%D0%B0%D1%86%D0%B8%D1%8F-%D0%B7%D0%B0%D0%BA%D0%B0%D0%B7%D0%B0-%D0%BE%D1%82-%D0%B8%D0%BD%D1%82%D0%B5%D1%80%D0%BD%D0%B5%D1%82-%D0%BC%D0%B0%D0%B3%D0%B0%D0%B7%D0%B8%D0%BD%D0%B0)
  - [x] [получение квитанции в PDF](#%D0%9F%D0%B5%D1%87%D0%B0%D1%82%D1%8C-%D0%BA%D0%B2%D0%B8%D1%82%D0%B0%D0%BD%D1%86%D0%B8%D0%B8-%D0%BA-%D0%B7%D0%B0%D0%BA%D0%B0%D0%B7%D1%83)
  - [x] [получение почтовых этикеток в PDF](#%D0%9F%D0%B5%D1%87%D0%B0%D1%82%D1%8C-%D0%A8%D0%9A-%D0%BC%D0%B5%D1%81%D1%82)
  - [x] [удаление заказов](#%D0%A3%D0%B4%D0%B0%D0%BB%D0%B5%D0%BD%D0%B8%D0%B5-%D0%B7%D0%B0%D0%BA%D0%B0%D0%B7%D0%B0)
  - [x] [получение информации по заказам (отчёт «Информация по заказам»)](#%D0%9E%D1%82%D1%87%D0%B5%D1%82-%D0%98%D0%BD%D1%84%D0%BE%D1%80%D0%BC%D0%B0%D1%86%D0%B8%D1%8F-%D0%BF%D0%BE-%D0%B7%D0%B0%D0%BA%D0%B0%D0%B7%D0%B0%D0%BC)
  - [x] [трекинг заказов (отчёт «Статусы заказов»)](#%D0%A2%D1%80%D0%B5%D0%BA%D0%B8%D0%BD%D0%B3)
  - [ ] прозвон получателя
  - [ ] вызов курьера
 - [x] выбор базового URL интерфейса
 - [x] [поддержка Laravel 5.1+](#laravel-51)

Работа с большинством методов API возможна только при наличии договора со СДЭК. Методы, отмеченные значком :unlock:, доступны без договора в ограниченном объеме (рассчитываются только публичные тарифы, без скидок).

## Установка

```bash
composer require sanmai/cdek-sdk
```

Требования — минимальны. Нужен PHP 7.0 или выше. Работа протестирована под PHP 7.0, 7.1, 7.2.

## Инициализация

```php
require_once 'vendor/autoload.php';

\Doctrine\Common\Annotations\AnnotationRegistry::registerLoader('class_exists');

$client = new \CdekSDK\CdekClient('account', 'password');
```
Реквизиты доступа следует [запросить у СДЭК](https://www.cdek.ru/clients/integrator.html). Обычные логин и пароль не подходят.

Далее для всей работы с API используются методы объёкта `$client`, который мы получили выше.

## Использование

Перечень основных методов класса `CdekClient` ниже.

| Задача| Метод | Аргумент |
| ----- | -------------- | ----- |
| [Удаление заказа](#%D0%A3%D0%B4%D0%B0%D0%BB%D0%B5%D0%BD%D0%B8%D0%B5-%D0%B7%D0%B0%D0%BA%D0%B0%D0%B7%D0%B0) | `sendDeleteRequest` | `DeleteRequest` |
| Получение списка ПВЗ | `sendPvzListRequest` | `PvzListRequest` |
| [Регистрация заказа от ИМ](#%D0%A0%D0%B5%D0%B3%D0%B8%D1%81%D1%82%D1%80%D0%B0%D1%86%D0%B8%D1%8F-%D0%B7%D0%B0%D0%BA%D0%B0%D0%B7%D0%B0-%D0%BE%D1%82-%D0%B8%D0%BD%D1%82%D0%B5%D1%80%D0%BD%D0%B5%D1%82-%D0%BC%D0%B0%D0%B3%D0%B0%D0%B7%D0%B8%D0%BD%D0%B0) | `sendDeliveryRequest` | `DeliveryRequest` |
| [Отчет "Информация по заказам"](#%D0%9E%D1%82%D1%87%D0%B5%D1%82-%D0%98%D0%BD%D1%84%D0%BE%D1%80%D0%BC%D0%B0%D1%86%D0%B8%D1%8F-%D0%BF%D0%BE-%D0%B7%D0%B0%D0%BA%D0%B0%D0%B7%D0%B0%D0%BC) | `sendInfoReportRequest` | `InfoReportRequest` |
| [Расчёт стоимости доставки](#%D0%A0%D0%B0%D1%81%D1%87%D1%91%D1%82-%D1%81%D1%82%D0%BE%D0%B8%D0%BC%D0%BE%D1%81%D1%82%D0%B8-%D0%B4%D0%BE%D1%81%D1%82%D0%B0%D0%B2%D0%BA%D0%B8) | `sendCalculationRequest` | `CalculationRequest` |
| [Отчет "Статусы заказов"](#%D0%A2%D1%80%D0%B5%D0%BA%D0%B8%D0%BD%D0%B3) | `sendStatusReportRequest` | `StatusReportRequest` |
| [Печать квитанции к заказу](#%D0%9F%D0%B5%D1%87%D0%B0%D1%82%D1%8C-%D0%BA%D0%B2%D0%B8%D1%82%D0%B0%D0%BD%D1%86%D0%B8%D0%B8-%D0%BA-%D0%B7%D0%B0%D0%BA%D0%B0%D0%B7%D1%83) | `sendPrintReceiptsRequest` | `PrintReceiptsRequest` |
| [Печать ШК-мест](#%D0%9F%D0%B5%D1%87%D0%B0%D1%82%D1%8C-%D0%A8%D0%9A-%D0%BC%D0%B5%D1%81%D1%82) | `sendPrintLabelsRequest` | `PrintLabelsRequest` |

### Расчёт стоимости доставки

```php
use CdekSDK\Requests\CalculationRequest;

// для выполнения авторизованного запроса используется
// $request = CalculationRequest::withAuthorization();
// $request->set...() и так далее

$request = (new CalculationRequest())
    ->setSenderCityPostCode('295000')
    ->setReceiverCityPostCode('652632')
    ->setTariffId(1)
    ->addPackage([
        'weight' => 0.2,
        'length' => 25,
        'width' => 15,
        'height' => 10,
    ]);

$response = $client->sendCalculationRequest($request);
/** @var \CdekSDK\Responses\CalculationResponse $response */

var_dump($response->getPrice());
// double(1250)
```

### Регистрация заказа от интернет-магазина

Названия полей соответствуют названиям полей [в официальной документации](https://confluence.cdek.ru/x/gUju).

```php
use CdekSDK\Common\Address;
use CdekSDK\Common\City;
use CdekSDK\Common\Item;
use CdekSDK\Common\Order;
use CdekSDK\Common\Package;
use CdekSDK\Requests\DeliveryRequest;

$order = new Order([
    'Number' => 'TEST-123456',
    'SendCity' => City::create([
        'Code' => 44, // Москва
    ]),
    'RecCity' => City::create([
        'PostCode' => '630001', // Новосибирск
    ]),
    'RecipientName' => 'Иван Петров',
    'RecipientEmail' => 'petrov@test.ru',
    'Phone' => '+7 (383) 202-22-50',
    'TariffTypeCode' => 139, // Посылка дверь-дверь от ИМ
]);

$order->setAddress(Address::create([
    'Street' => 'Холодильная улица',
    'House' => '16',
    'Flat' => '22',
]));

$order->addPackage(Package::create([
    'Number' => 'TEST-123456',
    'BarCode' => 'TEST-123456',
    'Weight' => 500, // Общий вес (в граммах)
    'SizeA' => 10, // Длина (в сантиметрах), в пределах от 1 до 1500
    'SizeB' => 10,
    'SizeC' => 10,
])->addItem(Item::create('NN0001', 500, 0, 120, 2, 'Test item')));

$request = new DeliveryRequest([
    'Number' => 'TESTING123',
]);
$request->addOrder($order);

$response = $client->sendDeliveryRequest($request);

foreach ($response->getMessages() as $message) {
    if ($message->isError()) {
        // обработка ошибки
    }
}

foreach ($response->getOrders() as $order) {
    // сверяем данные заказа, записываем номер
    $order->getNumber();
    $order->getDispatchNumber();
    break;
}
```

### Печать квитанции к заказу

Для подготовки документов необходимо указывать или номер заказа СДЭК, DispatchNumber, или номер заказа ИМ и дату через объёкт заказа.

```php
use CdekSDK\Requests\PrintReceiptsRequest;
use CdekSDK\Responses\FileResponse;
use CdekSDK\Responses\PrintErrorResponse;

$request = new PrintReceiptsRequest();
$request->addOrder(Order::withDispatchNumber($dispatchNumber));

$response = $client->sendPrintReceiptsRequest($request);

// Возвращаем содержимое PDF файла...
if ($response instanceof FileResponse) {
    return (string) $response->getBody();
}

// Или обрабатываем возможные ошибки
if ($response instanceof PrintErrorResponse) {
    foreach ($response->getMessages() as $message) {
        $message->isError();
        $message->getText();
    }
}
```

Также можно указывать в запросе сами объекты заказов, полученные из других методов. Или же можно создать заказ прямо на месте, имея известные `Number` и `Date`:

```
$request = new PrintReceiptsRequest();
$request->addOrder($orderFromAnotherResponse);
$request->addOrder(Order::withNumberAndDate($number, new \DateTime($dateString)));
```

### Печать ШК-мест

Печать ШК-мест производится по такому же алгоритму что и печать квитанций.

```php
use CdekSDK\Requests\PrintLabelsRequest;
use CdekSDK\Responses\FileResponse;
use CdekSDK\Responses\PrintErrorResponse;

$request = new PrintLabelsRequest([
    'PrintFormat' => PrintLabelsRequest::PRINT_FORMAT_A5,
]);
$request->addOrder(Order::withDispatchNumber($dispatchNumber));

$response = $client->sendPrintLabelsRequest($request);

// Возвращаем содержимое PDF файла...
if ($response instanceof FileResponse) {
    return (string) $response->getBody();
}

// Или обрабатываем возможные ошибки
if ($response instanceof PrintErrorResponse) {
    foreach ($response->getMessages() as $message) {
        $message->isError();
        $message->getText();
    }
}
```

### Удаление заказа

```php
use CdekSDK\Common\Order;
use CdekSDK\Requests\DeleteRequest;

$response = $client->sendDeleteRequest(DeleteRequest::create([
    'Number' => 'TESTING123',
])->addOrder(new Order([
    'Number' => 'TEST-123456',
])));

/** @var $response \CdekSDK\Responses\DeleteResponse */
foreach ($response->getMessages() as $message) {
    if ($message->isError()) {
        // обрабатываем ошибки
        $message->getText();
    }
}

foreach ($response->getOrders() as $order) {
    // проверяем номера удалённых заказов
    $order->getNumber(); // должно быть 'TEST-123456'
}
```

### Трекинг

Он же отчет "Статусы заказов", используется для получения отчета по статусам заказов, включая историю изменения статусов.

```php
use CdekSDK\Requests\StatusReportRequest;
use CdekSDK\Common\ChangePeriod;
use CdekSDK\Common\Order;

$request = new StatusReportRequest();
// можно указывать или всё сразу, или только диапазоны дат, или только конкретные заказы
$request->setChangePeriod(new ChangePeriod(new \DateTime('-1 day'), new \DateTime('+1 day')));
$request->addOrder(Order::withDispatchNumber($dispatchNumber));

$response = $client->sendStatusReportRequest($request);

foreach ($response->getOrders() as $order) {
    $order->ActNumber;
    $order->getNumber();
    $order->getDispatchNumber();
    $order->DeliveryDate;
    $order->getRecipientName();

    $order->getStatus()->getDescription();
    $order->getStatus()->getDate();
    $order->getStatus()->getCode();
    $order->getStatus()->getCityCode();
    $order->getStatus()->getCityName();

    $order->getReason()->Code;
    $order->getReason()->Description;
    $order->getReason()->Date;

    $order->getDelayReason()->Code;
    $order->getDelayReason()->Description;
    $order->getDelayReason()->Date;
}
```

### Отчет "Информация по заказам"

Отчет используется для получения детальной информации по заказам.

```php
use CdekSDK\Common\ChangePeriod;
use CdekSDK\Common\Order;
use CdekSDK\Requests\InfoReportRequest;

$request = new InfoReportRequest();
$request->setChangePeriod(new ChangePeriod(new \DateTime('-1 day'), new \DateTime('+1 day')));
// можно искать только по номерам, без дат
$request->addOrder(Order::withDispatchNumber($dispatchNumber));

$response = $client->sendInfoReportRequest($request);

foreach ($response->getMessages() as $message) {
    if ($message->isError()) {
        // Обрабатываем ошибки
        $message->getText();
    }
}

foreach ($response->getOrders() as $order) {
    $order->getNumber();
    $order->getSenderCity()->getName();
    $order->getRecipientCity()->getName();

    foreach ($order->getPackages() as $package) {
        $package->getBarCode();
        $package->getVolumeWeight();
    }

    foreach ($order->getAdditionalServices() as $service) {
        $service->getServiceCode();
        $service->getSum();
    }
}
```

### Сервис-провайдер для Laravel 5.1+

```php
// config/app.php

    'providers' => [
        // ...

        \CdekSDK\LaravelCdekServiceProvider::class

        // ...
    ]

// config/services.php

    'cdek' => [
        'account'  => env('CDEK_ACCOUNT', ''),
        'password' => env('CDEK_PASSWORD', ''),
    ],
```

## Авторы и ссылки

Эта библиотека - хард форк библиотеки [appwilio/cdek-sdk](https://github.com/appwilio/cdek-sdk) с поддержкой более старых версий PHP и расширенной поддержкой API. Обратная совместимость с исходной библиотекой не гарантируется, но фичи и исправления будут переноситься оттуда сюда по мере возможности. Если что-то пропустили, [дайте знать](https://github.com/sanmai/cdek-sdk/issues).

Авторы-создатели исходной библиотеки: [JhaoDa](https://github.com/jhaoda) и [greabock](https://github.com/greabock).

## Лицензия

Данный SDK распространяется под лицензией [MIT](http://opensource.org/licenses/MIT).

This project is licensed under the terms of the MIT license.
