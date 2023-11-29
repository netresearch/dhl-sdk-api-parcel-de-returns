<?php

/**
 * See LICENSE.md for license details.
 */

declare(strict_types=1);

namespace Dhl\Sdk\ParcelDe\Returns\Model\RequestType;

class Shipper implements \JsonSerializable
{
    /**
     * An optional, additional line of name information.
     */
    private ?string $name2 = null;

    /**
     * An optional, additional line of name information.
     */
    private ?string $name3 = null;

    /**
     * This is just the street name.
     */
    private ?string $addressStreet = null;

    /**
     * This is just the house number.
     */
    private ?string $addressHouse = null;

    /**
     * Line 2 of the street address. Most of the time this is not needed and not printed to most labels.
     *
     * Example: 3rd Floor
     */
    private ?string $additionalAddressInformation1 = null;

    /**
     * Line 3 of the street address. Rarely needed and not printed to most labels.
     *
     * Example: Apartment 12
     */
    private ?string $additionalAddressInformation2 = null;

    /**
     * Three-letter country code.
     */
    private ?string $country = null;

    /**
     * Contact email address.
     */
    private ?string $email = null;

    /**
     * Contact phone number.
     */
    private ?string $phone = null;

    /**
     * Region code.
     */
    private ?string $state = null;

    /**
     * @param string $name1 Line 1 of name information
     * @param string $city
     * @param string $postalCode Postal code, relaxed UPU version.
     */
    public function __construct(
        private readonly string $name1,
        private readonly string $city,
        private readonly string $postalCode
    ) {
    }

    public function setName2(?string $name2): void
    {
        $this->name2 = $name2;
    }

    public function setName3(?string $name3): void
    {
        $this->name3 = $name3;
    }

    public function setAddressStreet(?string $addressStreet): void
    {
        $this->addressStreet = $addressStreet;
    }

    public function setAddressHouse(?string $addressHouse): void
    {
        $this->addressHouse = $addressHouse;
    }

    public function setAdditionalAddressInformation1(?string $additionalAddressInformation1): void
    {
        $this->additionalAddressInformation1 = $additionalAddressInformation1;
    }

    public function setAdditionalAddressInformation2(?string $additionalAddressInformation2): void
    {
        $this->additionalAddressInformation2 = $additionalAddressInformation2;
    }

    public function setCountry(?string $country): void
    {
        $this->country = $country;
    }

    public function setEmail(?string $email): void
    {
        $this->email = $email;
    }

    public function setPhone(?string $phone): void
    {
        $this->phone = $phone;
    }

    public function setState(?string $state): void
    {
        $this->state = $state;
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
