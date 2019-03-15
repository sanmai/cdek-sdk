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

use CdekSDK\Common\State;
use CdekSDK\Serialization\Exception\LibXMLError;
use CdekSDK\Serialization\Exception\XmlErrorException;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;
use JMS\Serializer\Annotation\PostDeserialize;
use JMS\Serializer\DeserializationContext;
use JMS\Serializer\EventDispatcher\EventDispatcher;
use JMS\Serializer\EventDispatcher\Events;
use JMS\Serializer\EventDispatcher\ObjectEvent;
use JMS\Serializer\EventDispatcher\PreDeserializeEvent;
use JMS\Serializer\Handler\HandlerRegistryInterface;
use JMS\Serializer\Naming\IdenticalPropertyNamingStrategy;
use JMS\Serializer\Naming\SerializedNameAnnotationStrategy;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerBuilder;
use JMS\Serializer\SerializerInterface;

final class Serializer implements SerializerInterface
{
    /** @var bool */
    private static $configureAnnotationRegistry = true;

    /**
     * Настраивать ли AnnotationRegistry в автоматическом режиме, используя штатный автозагрузчик классов.
     */
    public static function doNotConfigureAnnotationRegistry()
    {
        self::$configureAnnotationRegistry = false;
    }

    private static $addGlobalIgnoredAnnotations = true;

    /** @var SerializerInterface */
    private $serializer;

    private $ctypeEnabled;

    public function __construct()
    {
        /** @var SerializerBuilder $builder */
        $builder = SerializerBuilder::create()->configureHandlers(function (HandlerRegistryInterface $registry) {
            $registry->registerSubscribingHandler(new NullableDateTimeHandler());
        });

        $builder->setPropertyNamingStrategy(new SerializedNameAnnotationStrategy(new IdenticalPropertyNamingStrategy()));

        /*
         * У СДЭК атрибуты могут быть как в верхнем, так и в нижнем регистре. Например, pvzCode="123" и PvzCode="123".
         * Чтобы не добавлять отдельные свойства для каждого вида написания (как в 4f5ca9d27), приведём все
         * атрибуты к единому виду, с главной большой буквы.
         */
        $builder->configureListeners(function (EventDispatcher $dispatcher) {
            /** @psalm-suppress MixedAssignment */
            $dispatcher->addListener(Events::PRE_DESERIALIZE, function (PreDeserializeEvent $event) {
                $data = $event->getData();
                if ($data instanceof \SimpleXMLElement) {
                    $event->setData($this->updateAttributesCase($data));
                }
            }, null, 'xml');


            $dispatcher->addListener(Events::POST_DESERIALIZE, function (ObjectEvent $event) {
                /** @var State $data */
                $data = $event->getObject();

                if ($data instanceof State) {
                    $data->setFinel();
                    // print_r($data); exit;
                }
            }, null, 'xml');
        });

        /** @psalm-suppress MixedAssignment */
        $this->serializer = $builder->build();

        // Can be disabled in certain environments (customized PHP build?)
        $this->ctypeEnabled = function_exists('\ctype_upper');

        if (self::$addGlobalIgnoredAnnotations) {
            // Ignore Phan/Psalm issue-suppressing annotations
            AnnotationReader::addGlobalIgnoredName('phan');
            AnnotationReader::addGlobalIgnoredName('psalm');
            AnnotationReader::addGlobalIgnoredName('template');
            // But do that just once
            self::$addGlobalIgnoredAnnotations = false;
        }

        if (self::$configureAnnotationRegistry) {
            self::configureAnnotationRegistry();
        }
    }

    /**
     * @psalm-suppress PossiblyNullIterator
     * @psalm-suppress MixedAssignment
     * @psalm-suppress MixedArrayAccess
     * @psalm-suppress MixedArgument
     */
    private function updateAttributesCase(\SimpleXMLElement $data): \SimpleXMLElement
    {
        assert($data->attributes() !== null);

        foreach ($data->attributes() as $attrName => $attrValue) {
            if ($this->ctypeEnabled && \ctype_upper($attrName[0])) {
                continue;
            }

            $data[ucfirst($attrName)] = $attrValue;
        }

        return $data;
    }

    /**
     * @see \JMS\Serializer\SerializerInterface::serialize()
     * @psalm-suppress MoreSpecificImplementedParamType
     *
     * @param object $data
     * @param string $format
     *
     * @return string
     */
    public function serialize($data, $format, SerializationContext $context = null)
    {
        return $this->serializer->serialize($data, $format, $context);
    }

    /**
     * @see \JMS\Serializer\SerializerInterface::deserialize()
     * @psalm-suppress MoreSpecificImplementedParamType
     *
     * @param mixed  $data
     * @param string $type
     * @param string $format
     */
    public function deserialize($data, $type, $format, DeserializationContext $context = null)
    {
        $data = (string) $data;

        try {
            return $this->serializer->deserialize($data, $type, $format, $context);
        } catch (\JMS\Serializer\Exception\XmlErrorException $e) {
            /**
             * @psalm-suppress MixedArgument
             */
            throw new XmlErrorException(LibXMLError::fromLibXMLError($e->getXmlError(), $data), $e->getCode(), $e);
        }
    }

    private static $annotationRegistryReady = false;

    private static function configureAnnotationRegistry()
    {
        if (self::$annotationRegistryReady) {
            return;
        }

        try {
            $reflectionClass = new \ReflectionClass(AnnotationRegistry::class);
            $reflectionProperty = $reflectionClass->getProperty('loaders');
            $reflectionProperty->setAccessible(true);
            // @codeCoverageIgnoreStart
        } catch (\ReflectionException $unused_exception) {
            // Свойство недоступно, или ещё что. Больше не пытаемся.
            self::$annotationRegistryReady = true;

            return;
        }
        // @codeCoverageIgnoreEnd

        // Настройку делаем только если её не сделали за нас.
        if ([] === $reflectionProperty->getValue()) {
            /** @phan-suppress-next-line PhanDeprecatedFunction */
            AnnotationRegistry::registerLoader('class_exists');
        }

        // @codeCoverageIgnoreStart
        self::$annotationRegistryReady = true;
        // @codeCoverageIgnoreEnd
    }
}
