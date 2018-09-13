# PHP SDK для API СДЭК

[![Build Status](https://travis-ci.org/sanmai/cdek-sdk.svg?branch=master)](https://travis-ci.org/sanmai/cdek-sdk)
[![Coverage Status](https://coveralls.io/repos/github/sanmai/cdek-sdk/badge.svg?branch=master)](https://coveralls.io/github/sanmai/cdek-sdk?branch=master)

Это будет самое полное SDK для [интеграции с программным комплексом СДЭК](https://www.cdek.ru/clients/integrator.html).

Возможности:

- :unlock:расчёт стоимости доставки
- :unlock:получение списка пунктов выдачи заказов (ПВЗ) с фильтрацией
- управление заказами
  - формирование новых заказов
  - генерация квитанции в PDF
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

## Конфигурация

```php
require_once 'vendor/autoload.php';

\Doctrine\Common\Annotations\AnnotationRegistry::registerLoader('class_exists');

$client = new \CdekSDK\CdekClient('account', 'password');
```

## Использование

### Расчёт стоимости доставки

```php
use CdekSDK\Requests\CalculationRequest;

// для выполнения авторизованного запроса используется
// $request = CalculationRequest::withAuthorization();

$request = (new CalculationRequest())
    ->setSenderCityPostCode('295000')
    ->setReceiverCityPostCode('652632')
    ->addGood([
        'weight' => 0.2,
        'length' => 10,
        'width'  => 10,
        'height' => 10,
    ]);

$response = $client->sendCalculationRequest($request)
```

### Трекинг

```php
use \CdekSDK\Common\Order;
use CdekSDK\Requests\StatusReportRequest;

$request = (new StatusReportRequest())
    ->setShowHistory();

$request->addOrder(new Order(['Number' => '89754564']));
$request->addOrder(new Order(['DispatchNumber' => '2222222222']));

$response = $client->sendStatusReportRequest($request);
```

## Авторы и ссылки

Эта библиотека - хард форк библиотеки [appwilio/cdek-sdk](https://github.com/appwilio/cdek-sdk) с поддержкой более старых версий PHP. Обратная совместимость с исходной библиотекой не гарантируется, но фичи и исправления будут переноситься оттуда сюда по возможности. Если что-то пропустили, [дайте знать](https://github.com/sanmai/cdek-sdk/issues).

Авторы-создатели исходной библиотеки:

- [greabock](https://github.com/greabock)
- [JhaoDa](https://github.com/jhaoda)

## Лицензия

Данный SDK распространяется под лицензией [MIT](http://opensource.org/licenses/MIT).

This project is licensed under the terms of the MIT license.
