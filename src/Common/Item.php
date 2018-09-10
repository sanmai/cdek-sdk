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

use JMS\Serializer\Annotation as JMS;

final class Item
{
    use Fillable;

    /**
     * @JMS\XmlAttribute
     * @JMS\SerializedName("WareKey")
     * @JMS\Type("string")
     *
     * @var string
     */
    protected $WareKey;

    /**
     * @JMS\XmlAttribute
     * @JMS\SerializedName("Cost")
     * @JMS\Type("float")
     *
     * @var float
     */
    protected $Cost;

    /**
     * @JMS\XmlAttribute
     * @JMS\SerializedName("Payment")
     * @JMS\Type("float")
     *
     * @var float
     */
    protected $Payment;

    /**
     * @JMS\XmlAttribute
     * @JMS\SerializedName("Weight")
     * @JMS\Type("int")
     *
     * @var int
     */
    protected $Weight;

    /**
     * @JMS\XmlAttribute
     * @JMS\SerializedName("Amount")
     * @JMS\Type("int")
     *
     * @var int
     */
    protected $Amount;

    /**
     * @JMS\XmlAttribute
     * @JMS\SerializedName("Comment")
     * @JMS\Type("string")
     *
     * @var string
     */
    protected $Comment;

    /**
     * @JMS\XmlAttribute
     * @JMS\SerializedName("DelivAmount")
     * @JMS\Type("int")
     *
     * @var int
     */
    protected $DelivAmount;

    /**
     * @suppress PhanUnusedPublicFinalMethodParameter
     *
     * @return \Appwilio\CdekSDK\Common\Item
     */
    public static function create(string $WareKey, float $Cost, float $Payment, int $Weight, int $Amount, string $Comment)
    {
        return new static(compact('WareKey', 'Cost', 'Payment', 'Weight', 'Amount', 'Comment'));
    }

    public function getWareKey(): string
    {
        return $this->WareKey;
    }

    public function getCost(): float
    {
        return $this->Cost;
    }

    public function getPayment(): float
    {
        return $this->Payment;
    }

    public function getWeight(): int
    {
        return $this->Weight;
    }

    public function getAmount(): int
    {
        return $this->Amount;
    }

    public function getComment(): string
    {
        return $this->Comment;
    }

    public function getDelivAmount(): int
    {
        return $this->DelivAmount;
    }
}
