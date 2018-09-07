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

namespace Tests\Appwilio\CdekSDK;

use Appwilio\CdekSDK\Requests\PvzListRequest;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

/**
 * @coversNothing
 */
class PvzListRequestTestCase extends TestCase
{
    public function test_can_get_params()
    {
        $request = (new PvzListRequest())
            ->setCityId(1)
            ->setRegionId(2)
            ->setCountryId(3)
            ->setPostCode('123456');

        Assert::assertEquals([
            'type' => 'PVZ',
            'cityid' => 1,
            'regionid' => 2,
            'countryid' => 3,
            'citypostcode' => '123456',
            'havecashles' => null,
            'weightmax' => null,
            'allowedcod' => null,
            'isdressingroom' => null,
        ], $request->getParams());
    }
}
