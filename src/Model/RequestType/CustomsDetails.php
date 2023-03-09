<?php

/**
 * See LICENSE.md for license details.
 */

declare(strict_types=1);

namespace Dhl\Sdk\ParcelDe\Returns\Model\RequestType;

class CustomsDetails implements \JsonSerializable
{
    /**
     * The customs items to be declared.
     *
     * @var CustomsItem[]
     */
    private array $items;

    /**
     * @param CustomsItem[] $items
     */
    public function __construct(array $items)
    {
        $this->items = $items;
    }

    /**
     * Specify data which should be serialized to JSON
     *
     * @return mixed[] Serializable object properties
     */
    public function jsonSerialize(): array
    {
        return get_object_vars($this);
    }
}
