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

namespace Appwilio\CdekSDK\Serialization;

use JMS\Serializer\Handler\DateHandler;
use JMS\Serializer\XmlDeserializationVisitor;

final class NullableDateTimeHandler extends DateHandler
{
    public function deserializeDateTimeFromXml(XmlDeserializationVisitor $visitor, $data, array $type)
    {
        if ((string) $data === '') {
            return null;
        }

        return parent::deserializeDateTimeFromXml($visitor, $data, $type);
    }

    public function deserializeDateTimeImmutableFromXml(XmlDeserializationVisitor $visitor, $data, array $type)
    {
        if ((string) $data === '') {
            return null;
        }

        return parent::deserializeDateTimeImmutableFromXml($visitor, $data, $type);
    }
}
