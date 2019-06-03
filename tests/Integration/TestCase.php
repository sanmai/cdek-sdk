<?php
/**
 * This code is licensed under the MIT License.
 *
 * Copyright (c) 2018 Appwilio (http://appwilio.com), greabock (https://github.com/greabock), JhaoDa (https://github.com/jhaoda)
 * Copyright (c) 2018 Alexey Kopytko <alexey@kopytko.com> and contributors
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

declare(strict_types=1);

namespace Tests\CdekSDK\Integration;

use CdekSDK\CdekClient;
use CdekSDK\Contracts\Response;
use GuzzleHttp\Client as GuzzleClient;

abstract class TestCase extends \PHPUnit\Framework\TestCase
{
    const TEST_HOST = 'integration.edu.cdek.ru';

    /** @var bool */
    private $isTesting = false;

    /**
     * @return \GuzzleHttp\Client|null
     * @psalm-suppress PossiblyFalseArgument
     */
    private function getGuzzleClient()
    {
        if (false === \getenv('CDEK_BASE_URL')) {
            return null;
        }

        if (\strpos(\getenv('CDEK_BASE_URL'), self::TEST_HOST)) {
            $this->isTesting = true;
        }

        return new GuzzleClient([
            'base_uri' => \getenv('CDEK_BASE_URL'),
            'verify'   => !\getenv('CI'), // Igonore SSL errors on the likes of Travis CI
        ]);
    }

    /**
     * @psalm-suppress MixedArgument
     * @psalm-suppress PossiblyFalseArgument
     */
    final protected function getClient(): CdekClient
    {
        if (false === \getenv('CDEK_ACCOUNT')) {
            $this->markTestSkipped('Integration testing disabled (CDEK_ACCOUNT missing).');
        }

        if (false === \getenv('CDEK_PASSWORD')) {
            $this->markTestSkipped('Integration testing disabled (CDEK_PASSWORD missing).');
        }

        $client = new CdekClient(\getenv('CDEK_ACCOUNT'), \getenv('CDEK_PASSWORD'), $this->getGuzzleClient());

        if (\in_array('--debug', $_SERVER['argv'])) {
            $client->setLogger(new DebuggingLogger());
        }

        return $client;
    }

    /**
     * @psalm-suppress MixedArgument
     */
    final protected function getAnonymousClient(): CdekClient
    {
        $client = new CdekClient('', '', $this->getGuzzleClient());

        if (\in_array('--debug', $_SERVER['argv'])) {
            $client->setLogger(new DebuggingLogger());
        }

        return $client;
    }

    final protected function skipIfKnownAPIErrorCode(Response $response, array $transientErrorCodes = [])
    {
        if (!$response->hasErrors()) {
            return;
        }

        $transientErrorCodes[] = 'ERROR_INTERNAL';

        foreach ($response->getMessages() as $message) {
            if ($message->getErrorCode() === '502') {
                $this->markTestSkipped("CDEK responded with an HTTP error code: {$message->getMessage()}");
            }
            if (\in_array($message->getErrorCode(), $transientErrorCodes)) {
                $this->markTestSkipped("CDEK failed with a known transient error code {$message->getErrorCode()}: {$message->getMessage()}");
            }
        }
    }

    final protected function isTestEndpointUsed(): bool
    {
        return $this->isTesting;
    }

    final protected function skipIfTestEndpointIsUsed(string $message = '')
    {
        if ($this->isTestEndpointUsed()) {
            $this->markTestSkipped($message);
        }
    }

    /**
     * @var string|null
     */
    private static $testNumber;

    private static function getTestNumber(): string
    {
        if (self::$testNumber !== null) {
            return self::$testNumber;
        }

        foreach ([
            'LOCAL_BUILD_ID',
            'TRAVIS_BUILD_ID',
        ] as $envVarName) {
            /** @var string|bool $value */
            $value = \getenv($envVarName);

            if ($value === false || \strlen($value) === 0) {
                continue;
            }

            return self::$testNumber = (string) $value;
        }

        return self::$testNumber = (string) \time();
    }

    final protected function formatTestNumber(string $format): string
    {
        return \sprintf($format, self::getTestNumber());
    }
}
