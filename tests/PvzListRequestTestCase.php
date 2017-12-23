<?php

use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;
use Appwilio\CdekSDK\Requests\PvzListRequest;

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
