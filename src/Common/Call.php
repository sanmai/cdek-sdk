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

namespace Appwilio\CdekSDK\Common;

use JMS\Serializer\Annotation as JMS;

/**
 * Class Call.
 */
final class Call
{
    use Fillable;

    /**
     * @JMS\SerializedName("CallGood")
     * @JMS\XmlList(entry="Good")
     * @JMS\Type("array<Appwilio\CdekSDK\Common\CallGood>")
     *
     * @var CallGood[]|array
     */
    public $CallGood = [];

    /**
     * @JMS\SerializedName("CallFail")
     * @JMS\XmlList(entry="Good")
     * @JMS\Type("array<Appwilio\CdekSDK\Common\CallFail>")
     *
     * @var CallFail[]|array
     */
    public $CallFail = [];

    /**
     * @JMS\SerializedName("CallDelay")
     * @JMS\XmlList(entry="Good")
     * @JMS\Type("array<Appwilio\CdekSDK\Common\CallDelay>")
     *
     * @var CallDelay[]|array
     */
    public $CallDelay;
}
