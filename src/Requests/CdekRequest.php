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

interface CdekRequest
{
    public function getMethod(): string;
    public function getAddress(): string;

    public function date(\DateTimeInterface $date): self;

    public function credentials(string $account, string $secure): self;
}
