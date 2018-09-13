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

use Appwilio\CdekSDK\Common\Order;
use Appwilio\CdekSDK\Requests\PrintReceiptsRequest;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Appwilio\CdekSDK\Requests\Template\PrintRequest
 * @covers \Appwilio\CdekSDK\Common\Order
 */
class PrintRequestTest extends TestCase
{
    public function test_it_rejects_order_without_dispatch_number()
    {
        $this->expectException(\BadMethodCallException::class);

        $request = new PrintReceiptsRequest();
        $request->addOrder(Order::create([
            'Number' => 'invalid',
        ]));
    }
}
