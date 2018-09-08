# SDK для СДЭК

[![Total Downloads](https://poser.pugx.org/appwilio/cdek-sdk/downloads)](https://packagist.org/packages/appwilio/cdek-sdk)
[![Latest Stable Version](https://poser.pugx.org/appwilio/cdek-sdk/version)](https://packagist.org/packages/appwilio/cdek-sdk)
[![License](https://poser.pugx.org/appwilio/cdek-sdk/license)](https://packagist.org/packages/appwilio/cdek-sdk)

### Это будет самое полное SDK для cdek.ru

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

> Работа с API возможна только при наличии договора с СДЭК (кроме методов, отмеченных как:unlock:).

## Установка

> Минимальные требования — PHP 7.1+.

```bash
composer require appwilio/cdek-sdk
```

### Laravel 5.1+
```php
// config/app.php

    'providers' => [
        // ...
        
        \Appwilio\CdekSDK\LaravelCdekServiceProvider::class
        
        // ...
    ]
    
// config/services.php

    'cdek' => [
        'account'  => env('CDEK_ACCOUNT', ''),
        'password' => env('CDEK_PASSWORD', ''),
    ],
```

### Иные фреймворки/без фреймворка
```php
require_once '../vendor/autoload.php';

\Doctrine\Common\Annotations\AnnotationRegistry::registerLoader('class_exists');

$client = new \Appwilio\CdekSDK\CdekClient('account', 'password');
```

## Использование

### Расчёт стоимости доставки

```php
use Appwilio\CdekSDK\Requests\CalculationRequest;

// для выполнения авторизованного запроса используется
// $request = CalculationRequest::withAuthorization();

$request = (CalculationRequest())
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
use \Appwilio\CdekSDK\Common\Order;
use Appwilio\CdekSDK\Requests\StatusReportRequest;

$request = (new StatusReportRequest())
    ->setShowHistory();

$request->addOrder(new Order(['Number' => '89754564']));
$request->addOrder(new Order(['DispatchNumber' => '2222222222']));

$response = $client->sendStatusReportRequest($request);
```

## Авторы

- [greabock](https://github.com/greabock)
- [JhaoDa](https://github.com/jhaoda)

## Лицензия

Данный SDK распространяется под лицензией [MIT](http://opensource.org/licenses/MIT).
