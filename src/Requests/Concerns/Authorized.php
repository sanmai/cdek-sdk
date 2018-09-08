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

namespace Appwilio\CdekSDK\Requests\Concerns;

use Appwilio\CdekSDK\Contracts\ShouldAuthorize;

trait Authorized
{
    /**
     * @JMS\XmlAttribute
     * @JMS\SerializedName("Date")
     * @JMS\Type("DateTimeImmutable<'Y-m-d'>")
     *
     * @var \DateTimeInterface
     */
    public $date;

    /**
     * @JMS\XmlAttribute
     * @JMS\SerializedName("Account")
     * @JMS\Type("string")
     *
     * @var string
     */
    public $account;

    /**
     * @JMS\XmlAttribute
     * @JMS\SerializedName("Secure")
     *
     * @var string
     */
    public $secure;

    /**
     * @suppress PhanTypeMismatchReturn
     */
    public function date(\DateTimeInterface $date): ShouldAuthorize
    {
        $this->date = $date;

        /** @var ShouldAuthorize $this */
        return $this;
    }

    /**
     * @suppress PhanTypeMismatchReturn
     */
    public function credentials(string $account, string $secure): ShouldAuthorize
    {
        $this->account = $account;
        $this->secure = $secure;

        /** @var ShouldAuthorize $this */
        return $this;
    }
}
