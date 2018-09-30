<?php

declare(strict_types = 1);

namespace Appwilio\CdekSDK\Responses\Types;

use JMS\Serializer\Annotation as JMS;

class AdditionalService
{
    /**
     * @JMS\Type("int")
     *
     * @var int
     */
    protected $id;

    /**
     * @JMS\Type("string")
     *
     * @var string
     */
    protected $title;

    /**
     * @JMS\Type("float")
     *
     * @var float
     */
    protected $price;

    public function getId(): int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getPrice(): float
    {
        return $this->price;
    }
}
