![](logo.png)

[![Latest Stable Version](https://poser.pugx.org/sanmai/cdek-sdk/v/stable)](https://packagist.org/packages/sanmai/cdek-sdk)
[![Telegram Chat](https://img.shields.io/badge/telegram-chat-blue.svg?logo=telegram)](https://t.me/phpcdeksdk)

<a href="https://www.cdek.ru/"><img align="right" src="https://gist.githubusercontent.com/sanmai/b105b3e2b5af030d5f1a8fb7db965f07/raw/308840dc54c3b1f02153f7318f02f87f30d4c5bd/cdek_logo.png"></a>

Перед вами полное SDK для [интеграции с программным комплексом СДЭК](https://cdek.ru/integration) с открытым [исходным кодом](https://github.com/sanmai/cdek-sdk) согласно [документации на API](https://confluence.cdek.ru/x/gUju).

## Установка  {: #install }

```bash
composer require sanmai/cdek-sdk
```
Требования — минимальны. Нужен PHP 7.0 или выше. Работа протестирована под PHP 7.0, 7.1, 7.2, 7.3, 7.4.

### Гарантии обратной совместимости {: #backward-compatibility }

При разработке этой библиотеки большое внимание уделяется обратной совместимости API в пределах основной версии. Если вы установили когда-то версию ветки 0.6, например 0.6.7, то после обновления до 0.6.8 или даже до 0.6.12 вы можете рассчитывать что весь ваш код будет работать точно так же как раньше, без необходимости что-то менять, при условии, конечно, что API самих СДЭК не поменялось. Такого же принципа работы с версиями [по умолчанию придерживается Composer](https://getcomposer.org/doc/articles/versions.md#caret-version-range-).

Гарантии обратной совместимости в части возвращаемых типов распостраняются только на имплементируемые ими интерфейсы. Если вы получали объект имплементирующий `Psr\Http\Message\ResponseInterface`, то такой же объёкт вы продолжите получать. Если у возвращенного объёкта был какой-то метод, то такой же метод будет у объекта в следующей неосновной версии. Конкретный тип может быть другим, рассчитывать на это не нужно, проверять принадлежность конкретному типу также не следует. [Как проверять ответы на ошибки.](#hasErrors)

Такие строгие гарантии обратной совместимости API были бы невозможны без идущей рука об руку с ними минимизации точек для расширения API: наследование для большинства классов не только не предусмотрено, но и просто невозможно. Впрочем, для удобства композиции есть необходимые интерфейсы. Мы исходим из того что добавить ещё интерфейсы проблемы не представляет, новые интерфейсы не ломают обратную совместимость.

После выхода версии 1.0 обратная совместимость будет поддерживаться в пределах мажорной версии.

## Инициализация {: #initialize }

```php
require_once 'vendor/autoload.php';

$client = new \CdekSDK\CdekClient('account', 'password');
```
Реквизиты доступа следует [запросить у СДЭК](https://www.cdek.ru/clients/integrator.html). Обычные логин и пароль не подходят. Если авторизация не нужна, логин и пароль можно указать пустые или пропустить вовсе.

Далее для всей работы с API используются методы объёкта `$client`, который мы получили выше.

Для подготовки запросов и ответов используются аннотации из Doctrine. Если вы не знаете что это, то ничего не нужно делать. Иначе обратите внимание на [замечания к совместному использованию AnnotationRegistry](#AnnotationRegistry).

### Работа в тестовой среде {: #testing }

Для работы в тестовой среде нужно указать другой базовый URI. Это делается следующим образом:

```php
$client = new \CdekSDK\CdekClient('Account', 'Secure', new \GuzzleHttp\Client([
    'base_uri' => 'https://integration.edu.cdek.ru',
]));
```

Реквизиты тестовых учётных записей можно найти [в документации на протокол](https://confluence.cdek.ru/pages/viewpage.action?pageId=15616129#id-%D0%9F%D1%80%D0%BE%D1%82%D0%BE%D0%BA%D0%BE%D0%BB%D0%BE%D0%B1%D0%BC%D0%B5%D0%BD%D0%B0%D0%B4%D0%B0%D0%BD%D0%BD%D1%8B%D0%BC%D0%B8(v1.5)-1.5.%D0%A2%D0%B5%D1%81%D1%82%D0%BE%D0%B2%D1%8B%D0%B5%D1%83%D1%87%D0%B5%D1%82%D0%BD%D1%8B%D0%B5%D0%B7%D0%B0%D0%BF%D0%B8%D1%81%D0%B8%D0%B8%D0%B8%D1%85%D0%BE%D0%B3%D1%80%D0%B0%D0%BD%D0%B8%D1%87%D0%B5%D0%BD%D0%B8%D1%8F).

## Использование {: #methods }

Перечень основных методов класса `CdekClient` ниже.

| Задача| Метод | Аргумент |
| ----- | -------------- | ----- |
| [Удаление заказа](#DeleteRequest) | `sendDeleteRequest` | `DeleteRequest` |
| [Получение списка ПВЗ](#PvzListRequest) | `sendPvzListRequest` | `PvzListRequest` |
| [Список субъектов РФ](#RegionsRequest) | `sendRegionsRequest` | `RegionsRequest` |
| [Список городов](#CitiesRequest) | `sendCitiesRequest` | `CitiesRequest` |
| [Регистрация заказа от ИМ](#DeliveryRequest) | `sendDeliveryRequest` | `DeliveryRequest` |
| [Регистрация заказа на доставку](#AddDeliveryRequest) | `sendAddDeliveryRequest` | `AddDeliveryRequest` |
| [Изменение заказа](#UpdateRequest) | `sendUpdateRequest` | `UpdateRequest` |
| [Регистрация результата прозвона](#ScheduleRequest) | `sendScheduleRequest` | `ScheduleRequest` |
| [Вызов курьера](#CallCourierRequest) | `sendCallCourierRequest` | `CallCourierRequest` |
| [Создание преалерта](#PreAlertRequest) | `sendPreAlertRequest` | `PreAlertRequest` |
| [Отчет "Информация по заказам"](#InfoReportRequest) | `sendInfoReportRequest` | `InfoReportRequest` |
| [Расчёт стоимости доставки с приоритетом](#CalculationRequest) | `sendCalculationRequest` | `CalculationRequest` |
| [Расчёт стоимости доставки без приоритета](#CalculationWithTariffListRequest) | `sendCalculationWithTariffListRequest` | `CalculationWithTariffListRequest` |
| [Отчет "Статусы заказов"](#StatusReportRequest) | `sendStatusReportRequest` | `StatusReportRequest` |
| [Печать квитанции к заказу](#PrintReceiptsRequest) | `sendPrintReceiptsRequest` | `PrintReceiptsRequest` |
| [Печать ШК-мест](#PrintLabelsRequest) | `sendPrintLabelsRequest` | `PrintLabelsRequest` |

Подробное описание параметров каждого метода [смотрите в документации СДЭК](https://www.cdek.ru/clients/integrator.html). Также обратите внимание на список [часто задаваемых вопросов по интеграции](https://www.cdek.ru/faq_integrator.html).

### Обработка ошибок {: #hasErrors }

Все возвращаемые ответы содержат методы для проверки на ошибку, также для получения списка сообщений включая сообщения об ошибках.

```php
/** @var \CdekSDK\Contracts\Response $response */
$response = $client->sendSomeRequest($request);

if ($response->hasErrors()) {
    // Обрабатываем ошибки
    foreach ($response->getMessages() as $message) {
        if ($message->getErrorCode() !== '') {
            // Это ошибка; бывают случаи когда сообщение с ошибкой - пустая строка.
            // Потому нужно смотреть и на код, и на описание ошибки.
            $message->getErrorCode();
            $message->getMessage();
        }
    }
}
```
В редких случаях при запросе и при работе с объектами могут возникнуть исключения.

#### TypeError {: #TypeError }

Кроме обычных ошибок при отправке запросов (например, из-за ошибочного XML, или из-за вышедшего времени ожидания результатов запроса), другим частым исключением является ошибка вида `TypeError`, которая возникает при работе с полученными объектами.

Например, при попытке получить возвратное отправление у заказа у которого такого нет, возникнет подобная ошибка:

> Uncaught TypeError: Return value of CdekSDK\Common\Order::getReturnOrder() must be an instance of CdekSDK\Common\Order, null returned

Если невозможно заранее знать что возвратного отправления у заказа нет, то можно попытаться получить его всё равно, поймав исключение.

```php
/** @var Order $order */
try {
    $returnOrder = $order->getReturnOrder();

    // Показываем номер возвратного заказа
    $returnOrder->getNumber();
    // ...
} catch (\TypeError $e) {
    // Возвратного заказа нет, показываем прочерк
    // ...
}
```

В других случаях тип возвращаемного значения может быть не указан в сигнатуре метода, а значит вместо исключения метод может вернуть `null`.

```php
if ($status = $order->getStatus()) {
    // Статус заказа есть, можно обратиться к его методам
    $status->getDescription();
}
```

Подобное расхождение в обработке отсутствующих значений существует в силу исторических причин, устранить которое невозможно без частичной потери [обратной совместимости](#backward-compatibility) (замена строгого возвращаемого типа на опциональный нарушает обратную совместимость). Эти различия могут быть частично или полностью устранены в следующей мажорной версии переходом на использование какого-то из двух методов, о чём будет обязательно сообщено.

### Получение списка ПВЗ {: #PvzListRequest }

```php
use CdekSDK\Requests;

$request = new Requests\PvzListRequest();
$request->setCityId(250);
$request->setType(Requests\PvzListRequest::TYPE_ALL);
$request->setCashless(true);
$request->setCodAllowed(true);
$request->setDressingRoom(true);

$response = $client->sendPvzListRequest($request);

if ($response->hasErrors()) {
    // обработка ошибок
}

/** @var \CdekSDK\Responses\PvzListResponse $response */
foreach ($response as $item) {
    /** @var \CdekSDK\Common\Pvz $item */
    // всевозможные параметры соответствуют полям из API СДЭК
    $item->Code;
    $item->Name;
    $item->Address;

    foreach ($item->OfficeImages as $image) {
        $image->getUrl();
    }
}
```

### Расчёт стоимости доставки с приоритетом {: #CalculationRequest }

```php
use CdekSDK\Requests;

// для выполнения запроса без авторизации используется
// $request = new Requests\CalculationRequest();
// $request->set...() и так далее

$request = new Requests\CalculationAuthorizedRequest();
$request->setSenderCityPostCode('295000')
    ->setReceiverCityPostCode('652632')
    ->setTariffId(1)
    ->addPackage([
        'weight' => 0.2,
        'length' => 25,
        'width'  => 15,
        'height' => 10,
    ]);

$response = $client->sendCalculationRequest($request);

if ($response->hasErrors()) {
    // обработка ошибок
}

/** @var \CdekSDK\Responses\CalculationResponse $response */
$response->getPrice();
// double(1250)
```

#### Характерные ошибки при расчёте {: #CalculationRequest-errors }

> "Невозможно осуществить доставку по этому направлению при заданных условиях"

Если используется запрос без авторизации при расчёте [по тарифам 136-139](https://confluence.cdek.ru/pages/viewpage.action?pageId=15616129#id-%D0%9F%D1%80%D0%BE%D1%82%D0%BE%D0%BA%D0%BE%D0%BB%D0%BE%D0%B1%D0%BC%D0%B5%D0%BD%D0%B0%D0%B4%D0%B0%D0%BD%D0%BD%D1%8B%D0%BC%D0%B8(v1.5)-%D0%9F%D1%80%D0%B8%D0%BB%D0%BE%D0%B6%D0%B5%D0%BD%D0%B8%D0%B51.%D0%A3%D1%81%D0%BB%D1%83%D0%B3%D0%B8(%D1%82%D0%B0%D1%80%D0%B8%D1%84%D1%8B)%D0%B8%D1%80%D0%B5%D0%B6%D0%B8%D0%BC%D1%8B%D0%B4%D0%BE%D1%81%D1%82%D0%B0%D0%B2%D0%BA%D0%B8%D0%A1%D0%94%D0%AD%D0%9A), то следует использовать запрос с авторизацией с реквизитами интернет-магазина. 

При прочих равных лучше всегда использовать запрос с авторизацией.

### Расчет стоимости по тарифам без приоритета {: #CalculationWithTariffListRequest }

Этот способ расчёта подразумевает получение нескольких расчётов стоимости доставки для каждого из запрошенных тарифов.

```
use CdekSDK\Requests;

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
```

### Список регионов/субъектов РФ {: #RegionsRequest }

```php
use CdekSDK\Requests;

$request = new Requests\RegionsRequest();
$request->setPage(0)->setSize(10);
$request->setCountryCode('RU');

$response = $client->sendRegionsRequest($request);

if ($response->hasErrors()) {
    // обработка ошибок
}

foreach ($response as $region) {
    /** @var \CdekSDK\Common\Region $region */
    $region->getUuid();
    $region->getName();
    $region->getPrefix();
    try {
        $region->getCode();
        $region->getCodeExt();
    } catch (\TypeError $e) {
        // У региона нет кода
    }
    $region->getFiasGuid();
    $region->getCountryName();
    $region->getCountryCodeISO();
    $region->getCountryCodeExt();
}
```

### Список городов {: #CitiesRequest }

```php
use CdekSDK\Requests;

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
```

### Автодополнение для города {: #CityAutocomplete }

Метод для автодополнения (автокомплита) города доступен для использования прямо на
клиенте, через JSONP. Пример запроса:

    https://api.cdek.ru/city/getListByTerm/jsonp.php?q=%D0%9C%D0%BE%D1%81%D0%BA%D0%B2%D0%B0&callback=foo

Так как этот запрос используется прямо на клиенте, то есть в браузере, этот метод не требует отдельной
реализации в этом SDK.

### Регистрация заказа от интернет-магазина {: #DeliveryRequest }

Названия полей соответствуют названиям полей [в официальной документации](https://confluence.cdek.ru/x/gUju).

```php
use CdekSDK\Common;
use CdekSDK\Requests;

$order = new Common\Order([
    'Number'   => 'TEST-123456',
    'SendCityCode'    => 44, // Москва
    'RecCityPostCode' => '630001', // Новосибирск
    'RecipientName'  => 'Иван Петров',
    'RecipientEmail' => 'petrov@test.ru',
    'Phone'          => '+7 (383) 202-22-50',
    'TariffTypeCode' => 139, // Посылка дверь-дверь от ИМ
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
```

### Регистрация заказа на доставку {: #AddDeliveryRequest }

Отличается необходимость указывать тип клиента, адрес забора груза. Без необходимости указывать состав посылок, но с указанием описания вложения.

```php
use CdekSDK\Common;
use CdekSDK\Requests;

$order = new Common\Order([
    'ClientSide' => Common\Order::CLIENT_SIDE_SENDER,
    'Number'     => 'TEST-123456',
    'SendCityCode'    => 44, // Москва
    'RecCityPostCode' => '630001', // Новосибирск
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
```

### Печать квитанции к заказу {: #PrintReceiptsRequest }

Для подготовки документов необходимо указывать или номер заказа СДЭК, DispatchNumber, или номер заказа ИМ и дату через объёкт заказа.

```php
use CdekSDK\Common;
use CdekSDK\Requests;

$request = new Requests\PrintReceiptsRequest([
    'CopyCount' => 4,
]);
$request->addOrder(Common\Order::withDispatchNumber($dispatchNumber));

$response = $client->sendPrintReceiptsRequest($request);

if ($response->hasErrors()) {
    // обработка ошибок
}

// Или возвращаем содержимое PDF файла...
return (string) $response->getBody();
```

Также можно указывать в запросе сами объекты заказов, полученные из других методов. Или же можно создать заказ прямо на месте, имея известные `Number` и `Date`:

```php
$request = new Requests\PrintReceiptsRequest();
$request->addOrder($orderFromAnotherResponse);
$request->addOrder(Common\Order::withNumberAndDate($number, new \DateTime($dateString)));
```

### Печать ШК-мест {: #PrintLabelsRequest }

Печать ШК-мест производится по такому же алгоритму что и печать квитанций.

```php
use CdekSDK\Common;
use CdekSDK\Requests;

$request = new Requests\PrintLabelsRequest([
    'PrintFormat' => Requests\PrintLabelsRequest::PRINT_FORMAT_A5,
]);
$request->addOrder(Common\Order::withDispatchNumber($dispatchNumber));

$response = $client->sendPrintLabelsRequest($request);

if ($response->hasErrors()) {
    // обработка ошибок
}

// Или возвращаем содержимое PDF файла...
return (string) $response->getBody();
```

### Удаление заказа {: #DeleteRequest }

```php
use CdekSDK\Common;
use CdekSDK\Requests;

$request = Requests\DeleteRequest::create([
    'Number' => 'TESTING123',
])->addOrder(new Common\Order([
    'Number' => 'TEST-123456',
]));

$response = $client->sendDeleteRequest($request);

if ($response->hasErrors()) {
    // обработка ошибок
}

foreach ($response->getOrders() as $order) {
    // проверяем номера удалённых заказов
    $order->getNumber(); // должно быть 'TEST-123456'
}
```

### Изменение заказа {: #UpdateRequest }

```php
use CdekSDK\Common;
use CdekSDK\Requests;

$request = Requests\UpdateRequest::create([
    'Number' => 'TESTING123',
])->addOrder(new Common\Order([
    'Number' => 'TEST-123456',
]));

$response = $client->sendUpdateRequest($request);

if ($response->hasErrors()) {
    // обработка ошибок
}

foreach ($response->getOrders() as $order) {
    // проверяем номера изменённых заказов
    $order->getNumber(); // должно быть 'TEST-123456'
}
```

### Вызов курьера {: #CallCourierRequest }

```php
use CdekSDK\Common;
use CdekSDK\Requests;

$request = Requests\CallCourierRequest::create()->addCall(Common\CallCourier::create([
    'Date'           => new \DateTime('tomorrow'),
    'DispatchNumber' => $dispatchNumber,
    'TimeBeg'        => new \DateTime('10:00'),
    'TimeEnd'        => new \DateTime('17:00'),
    'SendCityCode'   => 44,
    'SenderName'     => 'Проверка Тестович',
    'SendPhone'      => '+78001001010',
])->setAddress(Common\Address::create([
    'Street' => 'Тестовая',
    'House'  => '8',
    'Flat'   => '32',
])));

$response = $client->sendCallCourierRequest($request);

if ($response->hasErrors()) {
    // обработка ошибок
}

// Получаем номера заявок
foreach ($response->getNumbers() as $number) {
    $number; // ...
}
```

### Регистрация информации о результате прозвона {: #ScheduleRequest }

```php
use CdekSDK\Common;
use CdekSDK\Requests;

$request = new Requests\ScheduleRequest();
$request = $request->addOrder(Common\Order::create([
    'DispatchNumber' => '123456',
])->addAttempt(Common\Attempt::create([
    'ID'   => 500,
    'Date' => new \DateTime('next Monday'),
])->addPackage(Common\Package::create([
    'Number'  => 'TEST-123456',
    'BarCode' => 'TEST-123456',
    'Weight'  => 500,
])->addItem(new Common\Item([
    'WareKey' => 'NN0001',
    'Cost'    => 500,
    'Payment' => 0,
    'Weight'  => 120,
    'Amount'  => 2,
    'Comment' => 'Test item',
])))));

$response = $client->sendScheduleRequest($request);

if ($response->hasErrors()) {
    // обработка ошибок
}
```

### Создание преалерта {: #PreAlertRequest }

```php
use CdekSDK\Common;
use CdekSDK\Requests;

$request = new Requests\PreAlertRequest([
    'PvzCode'            => 'NSK333',
    'PlannedMeetingDate' => new \DateTime('2017-10-12'),
]);

$request->addOrder(Common\Order::withDispatchNumber('12345678'));

$response = $client->sendPreAlertRequest($request);

if ($response->hasErrors()) {
    // обработка ошибок
}
```

### Трекинг {: #StatusReportRequest }

Он же отчет "Статусы заказов", используется для получения отчета по статусам заказов, включая историю изменения статусов.

> Метод "Статусы заказов" и метод регистрации заказа обращаются к разным модулям системы, синхронизация между которыми может занимать до 10 минут. Рекомендуется запрос на получение статусов по заказу вызывать по истечении данного времени после создания заказа.

```php
use CdekSDK\Common;
use CdekSDK\Requests;

$request = new Requests\StatusReportRequest();
// можно указывать или всё сразу, или только диапазоны дат, или только конкретные заказы
$request->setChangePeriod(new Common\ChangePeriod(new \DateTime('-1 day'), new \DateTime('+1 day')));
$request->addOrder(Common\Order::withDispatchNumber($dispatchNumber));

// попросим показать историю изменения статусов заказов
$request->setShowHistory();

$response = $client->sendStatusReportRequest($request);

if ($response->hasErrors()) {
    // обработка ошибок
}

foreach ($response as $order) {
    /** @var \CdekSDK\Common\Order $order */
    $order->getActNumber();
    $order->getNumber();
    $order->getDispatchNumber();
    $order->getDeliveryDate();
    $order->getRecipientName();

    if ($status = $order->getStatus()) {
        $status->getDescription();
        $status->getDate();
        $status->getCode();
        $status->getCityCode();
        $status->getCityName();

        foreach ($status->getStates() as $state) {
            $state->getDescription();
            $state->getDate();
            $state->getCode();
            $state->getCityCode();
            $state->getCityName();
            $state->isFinal();
        }
    }

    $order->getReason()->getCode();
    $order->getReason()->getDescription();
    $order->getReason()->getDate();

    $order->getDelayReason()->getCode();
    $order->getDelayReason()->getDescription();
    $order->getDelayReason()->getDate();
}
```

### Отчет "Информация по заказам" {: #InfoReportRequest }

Отчет используется для получения детальной информации по заказам.

> Метод "Информация по заказам" и метод регистрации заказа обращаются к разным модулям системы, синхронизация между которыми может занимать до 10 минут. Рекомендуется запрос на получение статусов по заказу вызывать по истечении данного времени после создания заказа.

```php
use CdekSDK\Common;
use CdekSDK\Requests;

$request = new Requests\InfoReportRequest();
$request->setChangePeriod(new Common\ChangePeriod(new \DateTime('-1 day'), new \DateTime('+1 day')));
// можно искать только по номерам, без дат
$request->addOrder(Common\Order::withDispatchNumber($dispatchNumber));

$response = $client->sendInfoReportRequest($request);

if ($response->hasErrors()) {
    // обработка ошибок
}

foreach ($response as $order) {
    /** @var \CdekSDK\Common\Order $order */
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

### Замена базового URL интерфейса {: #base_uri }

[Перечень возможных URL в документации.](https://confluence.cdek.ru/pages/viewpage.action?pageId=15616129#id-%D0%9F%D1%80%D0%BE%D1%82%D0%BE%D0%BA%D0%BE%D0%BB%D0%BE%D0%B1%D0%BC%D0%B5%D0%BD%D0%B0%D0%B4%D0%B0%D0%BD%D0%BD%D1%8B%D0%BC%D0%B8(v1.5)-3.1.%D0%91%D0%B0%D0%B7%D0%BE%D0%B2%D1%8B%D0%B9URL%D0%B8%D0%BD%D1%82%D0%B5%D1%80%D1%84%D0%B5%D0%B9%D1%81%D0%B0)

```php
$account = getenv('CDEK_ACCOUNT');
$password = getenv('CDEK_PASSWORD');
$baseUri = getenv('CDEK_BASE_URL');
// Например, это может быть https://integration.cdek-asia.cn

$client = new \CdekSDK\CdekClient($account, $password, new \GuzzleHttp\Client([
    'base_uri' => $baseUri,
]));
```

Аналогичным образом можно увеличить таймаут для запросов, или подключить прокси-сервер, поменять [любые другие настройки](http://docs.guzzlephp.org/en/stable/request-options.html) запросов:

```php
$client = new \CdekSDK\CdekClient($account, $password, new \GuzzleHttp\Client([
    'base_uri' => \CdekSDK\CdekClient::STANDARD_BASE_URL,
    'timeout'  => 600,
]));
```

### Сервис-провайдер для Laravel 5.1+ {: #Laravel}

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
        'guzzle_options' => [ // необязательные параметры
            'base_uri' => 'https://integration.cdek-asia.cn',
            'timeout'  => 5,
        ],
    ],
```

### Отладка получаемых ответов {: #DebuggingLogger }

Посмотреть, что конкретно отвечает СДЭК на наши запросы и какие запросы мы посылаем сами можно используя [стандартный PSR-3 логгер](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-3-logger-interface.md), такой, как, например, [Monolog](https://github.com/Seldaek/monolog).

```php
$client->setLogger($monolog);
```

Текстовые запросы и ответы в исходном виде идут с уровнем `DEBUG`.

Пример класса, [реализующего минимальный интерфейс](https://github.com/sanmai/cdek-sdk/blob/master/tests/Integration/DebuggingLogger.php#L51-L57).

## Замечания {: #contribute}

- [Инструкции по доработке и тестированию.](https://github.com/sanmai/cdek-sdk/blob/master/CONTRIBUTING.md)

- [Общие инструкции по работе с GitHub.](https://www.alexeykopytko.com/2018/github-contributor-guide/) Если это ваш первый PR, очень рекомендуем ознакомиться.

### О форматах даты и времени {: #DateTimeImmutable }

Для указания даты и времени в запросах везде можно использовать ровно как `DateTime`, так и `DateTimeImmutable`.

### AnnotationRegistry {: #AnnotationRegistry }

Если вы не используете `AnnotationRegistry` где-то ещё, то никакой дополнительной настройки делать не требуется.

Если же вы используете `AnnotationRegistry` и в ней не настроен обычный автозагрузчик классов, то его следует подключить где-то до создания `CdekClient` следующим образом:

```php
\Doctrine\Common\Annotations\AnnotationRegistry::registerLoader('class_exists');
```

Если же нежелательно использовать обычный загрузчик классов, то можно отключить его автоматическую настройку:

```php
\CdekSDK\Serialization\Serializer::doNotConfigureAnnotationRegistry();
```

Обычно ничего этого делать не нужно, всё должно работать и так.

### Авторы и ссылки {: #legacy }

[Эта библиотека](https://github.com/sanmai/cdek-sdk) - хард-форк библиотеки [appwilio/cdek-sdk](https://github.com/appwilio/cdek-sdk) с поддержкой более старых версий PHP и расширенной поддержкой API. Обратная совместимость с исходной библиотекой не гарантируется, но фичи и исправления будут переноситься оттуда сюда по мере возможности. Если что-то пропустили, [дайте знать](https://github.com/sanmai/cdek-sdk/issues/new).

Авторы-создатели исходной библиотеки: [JhaoDa](https://github.com/jhaoda) и [greabock](https://github.com/greabock).

## Лицензия {: #license }

Данный SDK распространяется под лицензией MIT.

This project is licensed under the terms of the MIT license.
