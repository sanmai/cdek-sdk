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

use JMS\Serializer\Exception\RuntimeException;
use JMS\Serializer\GraphNavigatorInterface;
use JMS\Serializer\Handler\DateHandler;
use JMS\Serializer\Handler\SubscribingHandlerInterface;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\VisitorInterface;
use JMS\Serializer\XmlDeserializationVisitor;

/**
 * @method string format(\DateInterval $dateInterval)
 */
final class NullableDateTimeHandler implements SubscribingHandlerInterface
{
    const PLAIN_DATE_FORMAT = 'Y-m-d';

    /** @var DateHandler */
    private $dateHandler;

    public function __construct(string $defaultFormat = \DateTime::ATOM, string $defaultTimezone = 'UTC')
    {
        $this->dateHandler = new DateHandler($defaultFormat, $defaultTimezone);
    }

    public static function getSubscribingMethods()
    {
        $methods = DateHandler::getSubscribingMethods();

        $methods[] = [
            'type'      => \DateTimeImmutable::class,
            'format'    => 'xml',
            'direction' => GraphNavigatorInterface::DIRECTION_SERIALIZATION,
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
            return $this->dateHandler->deserializeDateTimeImmutableFromXml($visitor, $data, $type);
        } catch (RuntimeException $e) {
            return $this->parseDateTimeFallback($visitor, $data, $type, $e);
        }
    }

    /**
     * Fallback date format handler; for usage see below.
     *
     * @JMS\Type("DateTimeImmutable<'Y-m-d\TH:i:sP', '', 'Y-m-d\TH:i:sP', 'Y-m-d'>")
     *
     * @param XmlDeserializationVisitor $visitor
     * @param mixed                     $data
     *
     * @return \DateTimeInterface|null
     *
     * @psalm-suppress MixedAssignment
     * @psalm-suppress MixedInferredReturnType
     * @psalm-suppress MixedArrayAccess
     * @psalm-suppress MixedArrayAssignment
     * @psalm-suppress MixedArgument
     * @psalm-suppress MixedReturnStatement
     */
    private function parseDateTimeFallback(XmlDeserializationVisitor $visitor, $data, array $type, \Throwable $e)
    {
        if (!\array_key_exists(3, $type['params'])) {
            throw $e;
        }

        $format = $type['params'][2] = $type['params'][3];

        $datetime = $this->dateHandler->deserializeDateTimeImmutableFromXml($visitor, $data, $type);

        if ($datetime instanceof \DateTimeImmutable && self::PLAIN_DATE_FORMAT === $format) {
            $datetime = $datetime->setTime(0, 0, 0);
        }

        return $datetime;
    }

    public function serializeDateTimeInterface(VisitorInterface $visitor, \DateTimeInterface $date, array $type, SerializationContext $context)
    {
        // DateTimeInterface can't be implemented by user classes, so it's either \DateTime or \DateTimeImmutable

        if ($date instanceof \DateTime) {
            $date = \DateTimeImmutable::createFromMutable($date);
        }

        \assert($date instanceof \DateTimeImmutable);

        return $this->dateHandler->serializeDateTimeImmutable($visitor, $date, $type, $context);
    }

    public function __call($func, $args)
    {
        return $this->dateHandler->{$func}(...$args);
    }
}
