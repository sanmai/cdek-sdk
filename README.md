Компания СДЭК собирается [отключить поддержку протокола 1.5 до конца 2021 года](https://t.me/phpcdeksdk/6338). Просим или [переходить на версию 2](https://github.com/cdek-it/sdk2.0), или обращайтесь к своему менеджеру за разъяснениями. 

![](docs/logo.png)

# PHP SDK для API СДЭК

[![Latest Stable Version](https://poser.pugx.org/sanmai/cdek-sdk/v/stable)](https://packagist.org/packages/sanmai/cdek-sdk)
[![Build Status](https://travis-ci.org/sanmai/cdek-sdk.svg?branch=master)](https://travis-ci.org/sanmai/cdek-sdk)
[![Coverage Status](https://coveralls.io/repos/github/sanmai/cdek-sdk/badge.svg?branch=master)](https://coveralls.io/github/sanmai/cdek-sdk?branch=master)
[![Documentation Status](https://readthedocs.org/projects/cdek-sdk/badge/?version=latest)](https://cdek-sdk.readthedocs.io/?badge=latest)
[![Telegram Chat](https://img.shields.io/badge/telegram-chat-blue.svg?logo=telegram)](https://t.me/phpcdeksdk)

<a href="https://www.cdek.ru/"><img align="right" src="https://gist.githubusercontent.com/sanmai/b105b3e2b5af030d5f1a8fb7db965f07/raw/308840dc54c3b1f02153f7318f02f87f30d4c5bd/cdek_logo.png"></a>

Перед вами полное SDK для [интеграции с программным комплексом СДЭК](https://cdek.ru/integration).

Возможности:

- [x] расчёт тарифов и обращения к справочникам
  - [x] [расчёт стоимости доставки по тарифам с приоритетом](https://cdek-sdk.readthedocs.io/#CalculationRequest) :unlock: :closed_lock_with_key:
  - [x] [расчёт стоимости по тарифам без приоритета](https://cdek-sdk.readthedocs.io/#CalculationWithTariffListRequest) :unlock: :closed_lock_with_key:
  - [x] [получение списка пунктов выдачи заказов (ПВЗ) с фильтрацией](https://cdek-sdk.readthedocs.io/#PvzListRequest) :unlock:
  - [x] [получение списка регионов-субъектов РФ](https://cdek-sdk.readthedocs.io/#RegionsRequest) :unlock:
  - [x] [получение списка городов](https://cdek-sdk.readthedocs.io/#CitiesRequest) :unlock:
  - [x] [автодополнение для города](https://cdek-sdk.readthedocs.io/#CityAutocomplete) :unlock:
- [x] управление заказами
  - [x] [формирование новых заказов от ИМ](https://cdek-sdk.readthedocs.io/#DeliveryRequest)
  - [x] [оформление заказов на доставку](https://cdek-sdk.readthedocs.io/#AddDeliveryRequest)
  - [x] [получение квитанции в PDF](https://cdek-sdk.readthedocs.io/#PrintReceiptsRequest)
  - [x] [получение почтовых этикеток в PDF](https://cdek-sdk.readthedocs.io/#PrintLabelsRequest)
  - [x] [удаление заказов](https://cdek-sdk.readthedocs.io/#DeleteRequest)
  - [x] [изменение заказов](https://cdek-sdk.readthedocs.io/#UpdateRequest)
  - [x] [получение информации по заказам (отчёт «Информация по заказам»)](https://cdek-sdk.readthedocs.io/#InfoReportRequest)
  - [x] [трекинг заказов (отчёт «Статусы заказов»)](https://cdek-sdk.readthedocs.io/#StatusReportRequest)
  - [x] [прозвон получателя](https://cdek-sdk.readthedocs.io/#ScheduleRequest)
  - [x] [вызов курьера](https://cdek-sdk.readthedocs.io/#CallCourierRequest)
 - [x] [выбор базового URL интерфейса](https://cdek-sdk.readthedocs.io/#base_uri)
 - [x] [сервис-провайдер для Laravel 5.1+](https://cdek-sdk.readthedocs.io/#Laravel)
 - [x] [отладка получаемых ответов и посылаемых запросов](https://cdek-sdk.readthedocs.io/#DebuggingLogger)
- Чего-то нет в списке? [Напишите, сообщите.](https://github.com/sanmai/cdek-sdk/issues/new/choose)

Работа с большинством методов API возможна только при наличии договора со СДЭК. 

<table>
  <tr>
    <td>:unlock:</td>
    <td>Методы, отмеченные значком слева, доступны без договора в ограниченном объеме (рассчитываются только публичные тарифы, без скидок, без тарифов для ИМ).</td>
  </tr>
  <tr>
    <td>:closed_lock_with_key:</td>
    <td>Методы, отмеченные таким знаком, недоступны с тестовой учетной записью.</td>
  </tr>
</table>

## Версия API

Этот SDK предназначен для работы с первой версии API. 
Существуют SDK для второй версии API:
- [antistress-store/cdek-sdk-v2](https://github.com/AntistressStore/cdek-sdk-v2)
- [cdek-it/sdk2.0](https://github.com/cdek-it/sdk2.0)

## Установка

```bash
composer require sanmai/cdek-sdk
```
Требуется PHP 7.3 или выше. Работа протестирована под PHP 7.3, 7.4.

[Полная документация по всем методам.](https://cdek-sdk.readthedocs.io/)

### Авторы и ссылки

[Эта библиотека](https://github.com/sanmai/cdek-sdk) - хард-форк библиотеки [appwilio/cdek-sdk](https://github.com/appwilio/cdek-sdk) с поддержкой более старых версий PHP и расширенной поддержкой API. Обратная совместимость с исходной библиотекой не гарантируется, но фичи и исправления будут переноситься оттуда сюда по мере возможности. Если что-то пропустили, [дайте знать](https://github.com/sanmai/cdek-sdk/issues/new).

Авторы-создатели исходной библиотеки: [JhaoDa](https://github.com/jhaoda) и [greabock](https://github.com/greabock).

## Лицензия

Данный SDK распространяется [под лицензией MIT](LICENSE).

This project is licensed under the terms of the MIT license.

[![Maintainability](https://api.codeclimate.com/v1/badges/fd9cfee36167cb7af135/maintainability)](https://codeclimate.com/github/sanmai/cdek-sdk/maintainability)
[![Codacy Badge](https://api.codacy.com/project/badge/Grade/f99630e6f04944d09088ea5e198634b7)](https://app.codacy.com/app/sanmai/cdek-sdk?utm_source=github.com&utm_medium=referral&utm_content=sanmai/cdek-sdk&utm_campaign=Badge_Grade_Dashboard)
[![FOSSA Status](https://app.fossa.io/api/projects/git%2Bgithub.com%2Fsanmai%2Fcdek-sdk.svg?type=shield)](https://app.fossa.io/projects/git%2Bgithub.com%2Fsanmai%2Fcdek-sdk?ref=badge_shield)
