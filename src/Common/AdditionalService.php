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

class AdditionalService
{
    use Fillable;

    /**
     * @JMS\XmlAttribute
     * @JMS\SerializedName("ServiceCode")
     * @JMS\Type("int")
     *
     * @var int
     */
    protected $ServiceCode;

    /**
     * @JMS\XmlAttribute
     * @JMS\SerializedName("Sum")
     * @JMS\Type("float")
     *
     * @var float
     */
    protected $Sum;

    public function getServiceCode(): int
    {
        return $this->ServiceCode;
    }

    public function getSum(): float
    {
        return $this->Sum;
    }

    public static function create($code): self
    {
        return new static([
            'ServiceCode' => $code,
        ]);
    }
}
