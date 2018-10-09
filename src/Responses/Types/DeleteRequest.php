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

namespace CdekSDK\Responses\Types;

use CdekSDK\Contracts\HasErrorCode;
use CdekSDK\Requests\Concerns\OrdersAware;
use JMS\Serializer\Annotation as JMS;

/**
 * DeleteRequest - ответа на успешный одноимённый запрос.
 *
 * Пример данных:
 *
 * <DeleteRequest Msg="Удалено заказов:1" ><Order Number="TEST-123456" Msg="Заказ удален" /></DeleteRequest>
 * <DeleteRequest ErrorCode="ERR_NEED_ATTRIBUTE" Msg="Отсутствие обязательного атрибута: NUMBER"/>
 */
final class DeleteRequest implements HasErrorCode
{
    use OrdersAware;

    /**
     * @JMS\XmlAttribute
     * @JMS\Type("string")
     *
     * @var string
     */
    protected $Msg;

    /**
     * @JMS\XmlAttribute
     * @JMS\Type("string")
     *
     * @var string
     */
    protected $ErrorCode;

    public function getMessage(): string
    {
        return (string) $this->Msg;
    }

    public function getErrorCode(): string
    {
        return (string) $this->ErrorCode;
    }
}
