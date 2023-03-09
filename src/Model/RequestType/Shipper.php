<?php

/**
 * See LICENSE.md for license details.
 */

declare(strict_types=1);

namespace Dhl\Sdk\ParcelDe\Returns\Model\RequestType;

class Shipper implements \JsonSerializable
{
    /**
     * Line 1 of name information
     *
     * @var string
     */
    private string $name1;

    /**
     * An optional, additional line of name information.
     *
     * @var string|null
     */
    private ?string $name2;

    /**
     * An optional, additional line of name information.
     *
     * @var string|null
     */
    private ?string $name3;

    /**
     * This is just the street name.
     *
     * @var string|null
     */
    private ?string $addressStreet;

    /**
     * This is just the house number.
     *
     * @var string|null
     */
    private ?string $addressHouse;

    /**
     * Line 2 of the street address. Most of the time this is not needed and not printed to most labels.
     *
     * Example: 3rd Floor
     *
     * @var string|null
     */
    private ?string $additionalAddressInformation1;

    /**
     * Line 3 of the street address. Rarely needed and not printed to most labels.
     *
     * Example: Apartment 12
     *
     * @var string|null
     */
    private ?string $additionalAddressInformation2;

    /**
     * @var string
     */
    private string $city;

    /**
     * Three-letter country code.
     *
     * @var string|null
     */
    private ?string $country;

    /**
     * Contact email address.
     *
     * @var string|null
     */
    private ?string $email;

    /**
     * Contact phone number.
     *
     * @var string|null
     */
    private ?string $phone;

    /**
     * Postal code, relaxed UPU version.
     *
     * @var string
     */
    private string $postalCode;

    /**
     * Region code.
     *
     * @var string|null
     */
    private ?string $state;

    public function __construct(string $name1, string $city, string $postalCode)
    {
        $this->name1 = $name1;
        $this->city = $city;
        $this->postalCode = $postalCode;
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
