<?php

/**
 * See LICENSE.md for license details.
 */

declare(strict_types=1);

namespace Dhl\Sdk\ParcelDe\Returns\Model\RequestType;

class CustomsItem implements \JsonSerializable
{
    /**
     * Description of the declared item.
     *
     * @var string
     */
    private string $itemDescription;

    /**
     * Amount of the declared item(s).
     *
     * @var int
     */
    private int $packagedQuantity;

    /**
     * @var ItemWeight
     */
    private ItemWeight $itemWeight;

    /**
     * @var ItemValue
     */
    private ItemValue $itemValue;

    /**
     * Country where the returned item was produced.
     *
     * A valid country code consisting of three characters according to ISO 3166-1 alpha-3.
     *
     * @var string|null
     */
    private ?string $countryOfOrigin;

    /**
     * Harmonized System Code aka Customs tariff number.
     *
     * @var string|null
     */
    private ?string $hsCode;

    public function __construct(
        string $itemDescription,
        int $packagedQuantity,
        ItemWeight $itemWeight,
        ItemValue $itemValue
    ) {
        $this->itemDescription = $itemDescription;
        $this->packagedQuantity = $packagedQuantity;
        $this->itemWeight = $itemWeight;
        $this->itemValue = $itemValue;
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
