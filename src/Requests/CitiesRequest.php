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

use CdekSDK\Common\Fillable;
use CdekSDK\Contracts\ParamRequest;
use CdekSDK\Requests\Concerns\MagicSetters;
use CdekSDK\Requests\Concerns\ParamRequest as ParamRequestTrait;
use CdekSDK\Requests\Concerns\RequestCore;
use CdekSDK\Responses\CitiesResponse;

/**
 * @deprecated https://github.com/cdek-it/sdk2.0
 *
 * @method CitiesRequest setRegionCodeExt(mixed $value)
 * @method CitiesRequest setRegionCode(mixed $value)
 * @method CitiesRequest setRegionFiasGuid(mixed $value)
 * @method CitiesRequest setPage(mixed $value)
 * @method CitiesRequest setSize(mixed $value)
 * @method CitiesRequest setCountryCode(mixed $value)
 * @method CitiesRequest setCityName(mixed $value)
 * @method CitiesRequest setCityCode(mixed $value)
 * @method CitiesRequest setPostcode(mixed $value)
 * @method CitiesRequest setLang(mixed $value)
 */
final class CitiesRequest implements ParamRequest
{
    use RequestCore;
    use MagicSetters;
    use ParamRequestTrait;
    use Fillable;

    const METHOD = 'GET';
    const ADDRESS = '/v1/location/cities/xml';
    const RESPONSE = CitiesResponse::class;

    /**
     * Код субъекта РФ.
     *
     * @var string
     */
    protected $regionCodeExt;

    /**
     * Код региона в ИС СДЭК.
     *
     * @var int
     */
    protected $regionCode;

    /**
     * Код региона из ФИАС, UUID.
     *
     * @var string
     */
    protected $regionFiasGuid;

    /**
     * Номер страницы выборки результата. По умолчанию 0.
     *
     * @var int
     */
    protected $page;

    /**
     * Ограничение выборки результата. По умолчанию 1000.
     *
     * @var int
     */
    protected $size;

    /**
     * Код страны в формате ISO 3166-1 alpha-2.
     *
     * @var string
     */
    protected $countryCode;

    /**
     * Название города.
     *
     * @var string
     */
    protected $cityName;

    /**
     * Код города по базе СДЭК.
     *
     * @var int
     */
    protected $cityCode;

    /**
     * Почтовый индекс.
     *
     * @var string
     */
    protected $postcode;

    /**
     * Локализация. По умолчанию "rus".
     *
     * @var string
     */
    protected $lang;
}
