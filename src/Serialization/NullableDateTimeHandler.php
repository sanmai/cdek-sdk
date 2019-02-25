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

namespace CdekSDK\Serialization;

use JMS\Serializer\Context;
use JMS\Serializer\Exception\RuntimeException;
use JMS\Serializer\GraphNavigator;
use JMS\Serializer\Handler\DateHandler;
use JMS\Serializer\VisitorInterface;
use JMS\Serializer\XmlDeserializationVisitor;

final class NullableDateTimeHandler extends DateHandler
{
    public static function getSubscribingMethods()
    {
        $methods = parent::getSubscribingMethods();

        $methods[] = [
            'type'      => \DateTimeImmutable::class,
            'format'    => 'xml',
            'direction' => GraphNavigator::DIRECTION_SERIALIZATION,
            'method'    => 'serializeDateTimeInterface',
        ];

        return $methods;
    }

    public function deserializeDateTimeImmutableFromXml(XmlDeserializationVisitor $visitor, $data, array $type)
    {
        if ((string) $data === '') {
            return null;
        }

        try {
            return parent::deserializeDateTimeImmutableFromXml($visitor, $data, $type);
        } catch (RuntimeException $e) {
            if (!isset($type['params'][3])) {
                // Fallback date format; specify like so:
                // @JMS\Type("DateTimeImmutable<'Y-m-d\TH:i:sP', '', 'Y-m-d\TH:i:sP', 'Y-m-d'>")
                throw $e;
            }

            return $this->parseDateTimeFallback($data, $type);
        }
    }

    /**
     * @param mixed $data
     * @param array $type
     *
     * @throws RuntimeException
     *
     * @return \DateTimeImmutable
     *
     * @psalm-suppress MixedAssignment
     * @psalm-suppress PossiblyUndefinedArrayOffset
     * @psalm-suppress MixedArrayAccess
     * @psalm-suppress MixedArgument
     */
    private function parseDateTimeFallback($data, array $type)
    {
        $timezone = !empty($type['params'][1]) ? $type['params'][1] : 'UTC';
        $format = $type['params'][3];

        /** @var \DateTimeImmutable|false $datetime */
        $datetime = \DateTimeImmutable::createFromFormat($format, (string) $data, new \DateTimeZone($timezone));

        if (!$datetime instanceof \DateTimeImmutable) {
            throw new RuntimeException(sprintf('Invalid datetime "%s", expected format %s.', $data, $format));
        }

        if ($format === 'Y-m-d') {
            $datetime = $datetime->setTime(0, 0, 0);
        }

        if ($format === 'U') {
            $datetime = $datetime->setTimezone($timezone);
        }

        return $datetime;
    }

    public function serializeDateTimeInterface(VisitorInterface $visitor, \DateTimeInterface $date, array $type, Context $context)
    {
        // DateTimeInterface can't be implemented by user classes, so it's either \DateTime or \DateTimeImmutable

        if ($date instanceof \DateTime) {
            $date = \DateTimeImmutable::createFromMutable($date);
        }

        assert($date instanceof \DateTimeImmutable);

        return parent::serializeDateTimeImmutable($visitor, $date, $type, $context);
    }
}
