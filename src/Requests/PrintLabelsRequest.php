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

namespace Appwilio\CdekSDK\Requests;

use Appwilio\CdekSDK\Common\Fillable;
use Appwilio\CdekSDK\Requests\Concerns\Authorized;
use Appwilio\CdekSDK\Requests\Concerns\RequestCore;
use Appwilio\CdekSDK\Requests\Template\PrintRequest;
use JMS\Serializer\Annotation as JMS;

/**
 * Class PrintLabelsRequest.
 *
 * @JMS\XmlRoot(name="OrdersPackagesPrint")
 */
final class PrintLabelsRequest extends PrintRequest
{
    use Fillable, Authorized, RequestCore;

    const METHOD = 'POST';
    const ADDRESS = '/ordersPackagesPrint';

    const PRINT_FORMAT_A4 = 'A4';
    const PRINT_FORMAT_A5 = 'A5';
    const PRINT_FORMAT_A6 = 'A6';

    /**
     * @JMS\XmlAttribute
     * @JMS\SerializedName("PrintFormat")
     * @JMS\Type("int")
     *
     * @var string
     */
    private $PrintFormat = self::PRINT_FORMAT_A4;
}
