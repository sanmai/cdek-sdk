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

namespace CdekSDK;

class OrderStatuses
{
    /** @var array */
    public static $status_list = [
        1  => 'Создан', // Заказ зарегистрирован в базе данных СДЭК
        2  => 'Удален', // Заказ отменен ИМ после регистрации в системе до прихода груза на склад СДЭК в городе-отправителе
        3  => 'Принят на склад отправителя', // Оформлен приход на склад СДЭК в городе-отправителе.
        6  => 'Выдан на отправку в г. отправителе', // Оформлен расход со склада СДЭК в городе-отправителе. Груз подготовлен к отправке (консолидирован с другими посылками)
        16 => 'Возвращен на склад отправителя', // Повторно оформлен приход в городе-отправителе (не удалось передать перевозчику по какой-либо причине).
        7  => 'Сдан перевозчику в г. отправителе', // Зарегистрирована отправка в городе-отправителе. Консолидированный груз передан на доставку (в аэропорт/загружен машину)
        21 => 'Отправлен в г. транзит', // Зарегистрирована отправка в город-транзит. Проставлены дата и время отправления у перевозчика
        22 => 'Встречен в г. транзите', // Зарегистрирована встреча в городе-транзите
        13 => 'Принят на склад транзита', // Оформлен приход в городе-транзите
        17 => 'Возвращен на склад транзита', // Повторно оформлен приход в городе-транзите (груз возвращен на склад). Этот статус не означает возврат груза отправителю.
        19 => 'Выдан на отправку в г. транзите', // Оформлен расход в городе-транзите
        20 => 'Сдан перевозчику в г. транзите', // Зарегистрирована отправка у перевозчика в городе-транзите
        8  => 'Отправлен в г. получатель', // Зарегистрирована отправка в город-получатель, груз в пути
        9  => 'Встречен в г. получателе', // Зарегистрирована встреча груза в городе-получателе
        10 => 'Принят на склад доставки', // Оформлен приход на склад города-получателя, ожидает доставки до двери
        12 => 'Принят на склад до востребования', // Оформлен приход на склад города-получателя. Доставка до склада, посылка ожидает забора клиентом - покупателем ИМ
        11 => 'Выдан на доставку', // Добавлен в курьерскую карту, выдан курьеру на доставку
        18 => 'Возвращен на склад доставки', // Оформлен повторный приход на склад в городе-получателе. Этот статус не означает возврат груза отправителю
        4  => 'Вручен', // Успешно доставлен и вручен адресату (конечный статус)
        5  => 'Не вручен', // Покупатель отказался от покупки, возврат в ИМ (конечный статус)
    ];

    /**
     * Проверка является ли переданный код статуса заказа конечным
     *
     * @param int $code - код статуса
     *
     * @throws \InvalidArgumentException
     *
     * @return bool
     */
    public static function checkFinel(int $code): bool
    {
        if (!isset(self::$status_list[$code])) {
            throw new \InvalidArgumentException('Передан не существующий код статуса заказа СДЭК: '.$code);
        }
        if ($code === 4 || $code === 5) {
            return true;
        }

        return false;
    }

    /**
     * Возвращает текстовое описание статуса по коду.
     *
     * @param int $code - код статуса
     *
     * @throws \InvalidArgumentException
     *
     * @return string - статус
     */
    public static function getStatusByCode(int $code): string
    {
        if (!isset(self::$status_list[$code])) {
            throw new \InvalidArgumentException('Передан не существующий код статуса заказа СДЭК: '.$code);
        }

        if (is_array(self::$status_list)) {
            return (string) self::$status_list[$code];
        }

        return '';
    }
}
