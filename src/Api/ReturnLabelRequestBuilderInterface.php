<?php

/**
 * See LICENSE.md for license details.
 */

declare(strict_types=1);

namespace Dhl\Sdk\ParcelDe\Returns\Api;

use Dhl\Sdk\ParcelDe\Returns\Exception\RequestValidatorException;

/**
 * Interface ReturnLabelRequestBuilderInterface
 *
 * @api
 */
interface ReturnLabelRequestBuilderInterface
{
    public const WEIGHT_KG = 'kg';
    public const WEIGHT_G = 'g';

    public const CURRENCY_EUR = 'EUR';
    public const CURRENCY_USD = 'USD';
    public const CURRENCY_CZK = 'CZK';
    public const CURRENCY_GBP = 'GBP';
    public const CURRENCY_CHF = 'CHF';
    public const CURRENCY_SGD = 'SGD';

    /**
     * Set account related data.
     *
     * The name of the return recipient (receiverId) can be found in the
     * DHL business customer portal. The customer reference will be printed on
     * the label
     *
     * @param string $receiverId Receiver ID (Retourenempfängername)
     * @param string|null $customerReference Customer reference (usually order number)
     *
     * @return ReturnLabelRequestBuilderInterface
     */
    public function setAccountDetails(
        string $receiverId,
        ?string $customerReference = null
    ): ReturnLabelRequestBuilderInterface;

    /**
     * Set shipment reference (optional).
     *
     * The shipment reference is used to identify a return in the DHL business
     * customer portal listing. It is not printed on the label.
     *
     * @param string $shipmentReference
     *
     * @return ReturnLabelRequestBuilderInterface
     */
    public function setShipmentReference(string $shipmentReference): ReturnLabelRequestBuilderInterface;

    /**
     * Set the sender of the return shipment (the consumer).
     *
     * @param string $name
     * @param string $countryCode
     * @param string $postalCode
     * @param string $city
     * @param string $streetName
     * @param string $streetNumber
     * @param string|null $company
     * @param string|null $nameAddition
     * @param string[] $streetAddition
     * @param string|null $state
     *
     * @return ReturnLabelRequestBuilderInterface
     */
    public function setShipper(
        string $name,
        string $countryCode,
        string $postalCode,
        string $city,
        string $streetName,
        string $streetNumber,
        ?string $company = null,
        ?string $nameAddition = null,
        ?array $streetAddition = [],
        ?string $state = null
    ): ReturnLabelRequestBuilderInterface;

    /**
     * Set contact data of the sender (the consumer, optional).
     *
     * @param string $email
     * @param string|null $phone
     *
     * @return ReturnLabelRequestBuilderInterface
     */
    public function setShipperContact(string $email, ?string $phone = null): ReturnLabelRequestBuilderInterface;

    /**
     * @param float $value
     * @param string $uom
     *
     * @return ReturnLabelRequestBuilderInterface
     */
    public function setPackageWeight(float $value, string $uom = self::WEIGHT_KG): ReturnLabelRequestBuilderInterface;

    /**
     * @param float $value
     * @param string $currency
     *
     * @return ReturnLabelRequestBuilderInterface
     */
    public function setPackageValue(
        float $value,
        string $currency = self::CURRENCY_EUR
    ): ReturnLabelRequestBuilderInterface;

    /**
     * Add an item to be declared, mandatory if customs form ("CN23") is required.
     *
     * @param int $qty Amount of items declared per position.
     * @param string $description Description of the returned item.
     * @param float $value Monetary value of returned item.
     * @param string $currency Currency for item value.
     * @param float $weight Weight of the returned item.
     * @param string $weightUom Unit of measurement for item weight.
     * @param string|null $countryOfOrigin Country the returned item was produced.
     * @param string|null $hsCode Customs tariff number.
     *
     * @return ReturnLabelRequestBuilderInterface
     */
    public function addCustomsItem(
        int $qty,
        string $description,
        float $value,
        string $currency,
        float $weight,
        string $weightUom,
        ?string $countryOfOrigin = null,
        ?string $hsCode = null
    ): ReturnLabelRequestBuilderInterface;

    /**
     * Create the return label request and reset the builder data.
     *
     * @return \JsonSerializable
     *
     * @throws RequestValidatorException
     */
    public function create(): \JsonSerializable;
}
