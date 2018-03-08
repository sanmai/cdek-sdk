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

use Appwilio\CdekSDK\Contracts\ShouldAuthorize;
use Appwilio\CdekSDK\Requests\Concerns\Authorized;

/**
 * Class CalculationAuthorizedRequest
 *
 * @package Appwilio\CdekSDK\Requests
 */
class CalculationAuthorizedRequest extends CalculationRequest implements ShouldAuthorize
{
    use Authorized;

    public function getBody(): array
    {
        return array_merge(parent::getBody(), [
            'secure'      => $this->secure,
            'authLogin'   => $this->account,
            'dateExecute' => $this->date->format('Y-m-d'),
        ]);
    }
}
