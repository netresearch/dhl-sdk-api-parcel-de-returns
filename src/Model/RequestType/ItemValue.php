<?php

/**
 * See LICENSE.md for license details.
 */

declare(strict_types=1);

namespace Dhl\Sdk\ParcelDe\Returns\Model\RequestType;

class ItemValue implements \JsonSerializable
{
    public const CURRENCY_EUR = 'EUR';
    public const CURRENCY_USD = 'USD';
    public const CURRENCY_CZK = 'CZK';
    public const CURRENCY_GBP = 'GBP';
    public const CURRENCY_CHF = 'CHF';
    public const CURRENCY_SGD = 'SGD';

    /**
     * ISO 4217 three-digit currency code accepted. Recommended to use EUR where possible
     *
     * @var string
     */
    private string $currency;

    /**
     * Weight of item or shipment.
     *
     * @var float
     */
    private float $value;

    public function __construct(string $currency, float $value)
    {
        $this->currency = $currency;
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
