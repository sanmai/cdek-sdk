<?php

/**
 * This file is part of Cdek SDK package.
 *
 * © Appwilio (http://appwilio.com), greabock (https://github.com/greabock), JhaoDa (https://github.com/jhaoda)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Appwilio\CdekSDK\Requests;

use JMS\Serializer\Annotation as JMS;

trait XmlRequestRoot
{
    /**
     * @JMS\XmlAttribute
     * @JMS\SerializedName("Date")
     * @JMS\Type("DateTimeImmutable<'Y-m-d'>")
     *
     * @var \DateTime
     */
    public $Date;

    /**
     * @JMS\XmlAttribute
     * @JMS\SerializedName("Account")
     * @JMS\Type("string")
     *
     * @var string
     */
    public $Account;

    /**
     * @JMS\XmlAttribute
     * @JMS\SerializedName("Secure")
     *
     * @var string
     */
    public $Secure;

    public function date(\DateTimeInterface $date): CdekRequest
    {
        $this->Date = $date;

        /** @var CdekRequest $this */
        return $this;
    }

    public function credentials(string $account, string $secure): CdekRequest
    {
        $this->Account = $account;
        $this->Secure = $secure;

        /** @var CdekRequest $this */
        return $this;
    }

    public function getAddress(): string
    {
        return static::ADDRESS;
    }

    public function getMethod(): string
    {
        return static::METHOD;
    }
}
