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
| [Регистрация заказа](https://confluence.cdek.ru/x/gUju) | `sendDeliveryRequest` | `DeliveryRequest` |
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

Эта библиотека - хард форк библиотеки [appwilio/cdek-sdk](https://github.com/appwilio/cdek-sdk) с поддержкой более старых версий PHP и расширенным. Обратная совместимость с исходной библиотекой не гарантируется, но фичи и исправления будут переноситься оттуда сюда по мере возможности. Если что-то пропустили, [дайте знать](https://github.com/sanmai/cdek-sdk/issues).

Авторы-создатели исходной библиотеки: [JhaoDa](https://github.com/jhaoda) и [greabock](https://github.com/greabock).

## Лицензия

Данный SDK распространяется под лицензией [MIT](http://opensource.org/licenses/MIT).

This project is licensed under the terms of the MIT license.
