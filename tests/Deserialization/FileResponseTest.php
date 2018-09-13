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

namespace Tests\Appwilio\CdekSDK\Deserialization;

use Appwilio\CdekSDK\Responses\FileResponse;
use Psr\Http\Message\StreamInterface;

/**
 * @covers \Appwilio\CdekSDK\Responses\FileResponse
 */
class FileResponseTest extends TestCase
{
    public function test_unserialize_normal_date()
    {
        $stream = $this->createMock(StreamInterface::class);
        $stream->method('__toString')->willReturn('testing');

        $response = new FileResponse($stream);
        $this->assertSame('testing', (string) $response->getBody());
    }
}
