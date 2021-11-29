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

namespace CdekSDK\Requests;

use CdekSDK\Common\CallCourier;
use CdekSDK\Common\Fillable;
use CdekSDK\Contracts\ShouldAuthorize;
use CdekSDK\Contracts\XmlRequest;
use CdekSDK\Requests\Concerns\Authorized;
use CdekSDK\Requests\Concerns\RequestCore;
use CdekSDK\Responses\CallCourierResponse;
use JMS\Serializer\Annotation as JMS;

/**
 * @deprecated https://github.com/cdek-it/sdk2.0
 *
 * @JMS\XmlRoot(name="CallCourier")
 */
final class CallCourierRequest implements XmlRequest, ShouldAuthorize
{
    use Authorized;
    use RequestCore;
    use Fillable;

    const METHOD = 'POST';
    const ADDRESS = '/call_courier.php';
    const RESPONSE = CallCourierResponse::class;

    /**
     * @JMS\XmlList(entry = "Call", inline = true)
     * @JMS\Type("array<CdekSDK\Common\CallCourier>")
     *
     * @var CallCourier[]
     */
    protected $calls = [];

    /**
     * @JMS\XmlAttribute
     * @JMS\SerializedName("CallCount")
     * @JMS\Type("int")
     * @JMS\VirtualProperty()
     */
    public function getCallCount()
    {
        return \count($this->calls);
    }

    /** @return self */
    public function addCall(CallCourier $call)
    {
        $this->calls[] = $call;

        return $this;
    }
}
