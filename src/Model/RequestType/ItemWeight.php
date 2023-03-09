<?php

/**
 * See LICENSE.md for license details.
 */

declare(strict_types=1);

namespace Dhl\Sdk\ParcelDe\Returns\Model\RequestType;

class ItemWeight implements \JsonSerializable
{
    public const WEIGHT_G = 'g';
    public const WEIGHT_KG = 'kg';

    /**
     * Unit of measurement. Metric unit for weight.
     *
     * @var string
     */
    private string $uom;

    /**
     * Weight of item or shipment.
     *
     * @var float
     */
    private float $value;

    public function __construct(string $uom, float $value)
    {
        $this->uom = $uom;
        $this->value = $value;
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
