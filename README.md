# PHP SDK для API СДЭК

[![Build Status](https://travis-ci.org/sanmai/cdek-sdk.svg?branch=master)](https://travis-ci.org/sanmai/cdek-sdk)
[![Coverage Status](https://coveralls.io/repos/github/sanmai/cdek-sdk/badge.svg?branch=master)](https://coveralls.io/github/sanmai/cdek-sdk?branch=master)

Это будет самое полное SDK для [интеграции с программным комплексом СДЭК](https://www.cdek.ru/clients/integrator.html).

Возможности:

- :unlock: расчёт стоимости доставки
- :unlock: получение списка пунктов выдачи заказов (ПВЗ) с фильтрацией
- управление заказами
  - формирование новых заказов
  - получение квитанции в PDF
  - получение почтовых этикеток в PDF
  - удаление заказов
  - получение информации по заказам (отчёт «Информация по заказам»)
  - трекинг заказов (отчёт «Статусы заказов»)
  - [ ] прозвон получателя
  - [ ] вызов курьера

Работа с API возможна только при наличии договора с СДЭК (кроме методов, отмеченных знаком :unlock:).

## Установка

Минимальные требования — PHP 7.0.

```bash
composer require sanmai/cdek-sdk
```

## Инициализация

```php
require_once 'vendor/autoload.php';

\Doctrine\Common\Annotations\AnnotationRegistry::registerLoader('class_exists');

$client = new \CdekSDK\CdekClient('account', 'password');
```
Реквизиты доступа следует [запросить у СДЭК](https://www.cdek.ru/clients/integrator.html). Обычные логин и пароль не подходят.

Далее для всей работы с API используются методы объёкта `$client`, который мы получили выше.

## Использование

Полный перечень существующих методов ниже.

| Задача| Метод | Аргумент |
| ----- | -------------- | ----- |
| [Удаление заказа](https://confluence.cdek.ru/x/gUju) | `sendDeleteRequest` | `DeleteRequest` |
| [Получение списка ПВЗ](https://confluence.cdek.ru/x/gUju) | `sendPvzListRequest` | `PvzListRequest` |
| [Регистрация заказа от ИМ](https://confluence.cdek.ru/x/gUju) | `sendDeliveryRequest` | `DeliveryRequest` |
| [Отчет "Информация по заказам"](https://confluence.cdek.ru/x/gUju) | `sendInfoReportRequest` | `InfoReportRequest` |
| [Расчёт стоимости доставки](https://www.cdek.ru/clients/integrator.html) | `sendCalculationRequest` | `CalculationRequest` |
| [Отчет "Статусы заказов"](https://confluence.cdek.ru/x/gUju) | `sendStatusReportRequest` | `StatusReportRequest` |
| [Печать квитанции к заказу](https://confluence.cdek.ru/x/gUju) | `sendPrintReceiptsRequest` | `PrintReceiptsRequest` |
| [Печать ШК-мест](https://confluence.cdek.ru/x/gUju) | `sendPrintLabelsRequest` | `PrintLabelsRequest` |

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

Названия полей соответствуют названиям полей в официальной документации.

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

```php
use CdekSDK\Common\Order;
use CdekSDK\Requests\StatusReportRequest;

$request = (new StatusReportRequest())
    ->setShowHistory();

$request->addOrder(new Order(['Number' => '89754564']));
$request->addOrder(new Order(['DispatchNumber' => '2222222222']));

$response = $client->sendStatusReportRequest($request);
```

## Авторы и ссылки

Эта библиотека - хард форк библиотеки [appwilio/cdek-sdk](https://github.com/appwilio/cdek-sdk) с поддержкой более старых версий PHP и расширенной поддержкой API. Обратная совместимость с исходной библиотекой не гарантируется, но фичи и исправления будут переноситься оттуда сюда по мере возможности. Если что-то пропустили, [дайте знать](https://github.com/sanmai/cdek-sdk/issues).

Авторы-создатели исходной библиотеки: [JhaoDa](https://github.com/jhaoda) и [greabock](https://github.com/greabock).

## Лицензия

Данный SDK распространяется под лицензией [MIT](http://opensource.org/licenses/MIT).

This project is licensed under the terms of the MIT license.
