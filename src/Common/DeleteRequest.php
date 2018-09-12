<?php
/*
 * This file is part of Cdek SDK package.
 *
 * © Appwilio (http://appwilio.com), greabock (https://github.com/greabock), JhaoDa (https://github.com/jhaoda)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Appwilio\CdekSDK\Common;

use Appwilio\CdekSDK\Contracts\HasMessage;
use Appwilio\CdekSDK\Requests\Concerns\OrdersAware;
use JMS\Serializer\Annotation as JMS;

/**
 * DeleteRequest - ответа на успешный одноимённый запрос.
 *
 * Пример данных:
 *
 * <DeleteRequest Msg="Удалено заказов:1" ><Order Number="TEST-123456" Msg="Заказ удален" /></DeleteRequest>
 */
final class DeleteRequest implements HasMessage
{
    use OrdersAware;

    /**
     * @JMS\XmlAttribute
     * @JMS\SerializedName("Msg")
     * @JMS\Type("string")
     *
     * @var string
     */
    protected $Msg;

    public function getMessage(): string
    {
        return (string) $this->Msg;
    }
}
