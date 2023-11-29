<?php

/**
 * See LICENSE.md for license details.
 */

declare(strict_types=1);

namespace Dhl\Sdk\ParcelDe\Returns\Model\RequestType;

class ItemValue implements \JsonSerializable
{
    final public const CURRENCY_EUR = 'EUR';
    final public const CURRENCY_USD = 'USD';
    final public const CURRENCY_CZK = 'CZK';
    final public const CURRENCY_GBP = 'GBP';
    final public const CURRENCY_CHF = 'CHF';
    final public const CURRENCY_SGD = 'SGD';

    /**
     * @param string $currency ISO 4217 three-digit currency code accepted. Recommended to use EUR where possible
     * @param float $value Weight of item or shipment.
     */
    public function __construct(
        private readonly string $currency,
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
