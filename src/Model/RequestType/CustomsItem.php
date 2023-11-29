<?php

/**
 * See LICENSE.md for license details.
 */

declare(strict_types=1);

namespace Dhl\Sdk\ParcelDe\Returns\Model\RequestType;

class CustomsItem implements \JsonSerializable
{
    /**
     * Country where the returned item was produced.
     *
     * A valid country code consisting of three characters according to ISO 3166-1 alpha-3.
     */
    private ?string $countryOfOrigin = null;

    /**
     * Harmonized System Code aka Customs tariff number.
     */
    private ?string $hsCode = null;

    /**
     * @param string $itemDescription Description of the declared item.
     * @param int $packagedQuantity Amount of the declared item(s).
     * @param ItemWeight $itemWeight
     * @param ItemValue $itemValue
     */
    public function __construct(
        private readonly string $itemDescription,
        private readonly int $packagedQuantity,
        private readonly ItemWeight $itemWeight,
        private readonly ItemValue $itemValue
    ) {
    }

    public function setCountryOfOrigin(?string $countryOfOrigin): void
    {
        $this->countryOfOrigin = $countryOfOrigin;
    }

    public function setHsCode(?string $hsCode): void
    {
        $this->hsCode = $hsCode;
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
