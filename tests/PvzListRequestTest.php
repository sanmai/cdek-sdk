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
use PHPUnit\Framework\TestCase;

/**
 * @covers \Appwilio\CdekSDK\Requests\PvzListRequest
 */
class PvzListRequestTest extends TestCase
{
    public function test_can_get_params()
    {
        $request = (new PvzListRequest())
            ->setCityId(1)
            ->setRegionId(2)
            ->setCountryId(3)
            ->setPostCode('123456');

        $this->assertEquals([
            'type' => PvzListRequest::TYPE_PVZ,
            'cityid' => 1,
            'regionid' => 2,
            'countryid' => 3,
            'citypostcode' => '123456',
            'havecashles' => null,
            'weightmax' => null,
            'allowedcod' => null,
            'isdressingroom' => null,
        ], $request->getParams());

        $request = (new PvzListRequest())
            ->setType(PvzListRequest::TYPE_POSTOMAT)
            ->setCashless(true)
            ->setDressingRoom(false)
            ->setCodAllowed(true)
            ->setMaxWeight(100);

        $this->assertEquals([
            'type' => PvzListRequest::TYPE_POSTOMAT,
            'cityid' => null,
            'regionid' => null,
            'countryid' => null,
            'citypostcode' => null,
            'havecashles' => true,
            'weightmax' => 100,
            'allowedcod' => true,
            'isdressingroom' => false,
        ], $request->getParams());
    }
}
