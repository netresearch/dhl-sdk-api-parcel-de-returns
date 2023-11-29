<?php

/**
 * See LICENSE.md for license details.
 */

declare(strict_types=1);

namespace Dhl\Sdk\ParcelDe\Returns\Model\RequestType;

class ItemWeight implements \JsonSerializable
{
    final public const WEIGHT_G = 'g';
    final public const WEIGHT_KG = 'kg';

    /**
     * @param string $uom Unit of measurement. Metric unit for weight.
     * @param float $value Weight of item or shipment.
     */
    public function __construct(
        private readonly string $uom,
        private readonly float $value
    ) {
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
