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
    final public const WEIGHT_KG = 'kg';
    final public const WEIGHT_G = 'g';

    final public const CURRENCY_EUR = 'EUR';
    final public const CURRENCY_USD = 'USD';
    final public const CURRENCY_CZK = 'CZK';
    final public const CURRENCY_GBP = 'GBP';
    final public const CURRENCY_CHF = 'CHF';
    final public const CURRENCY_SGD = 'SGD';

    /**
     * Set the business customer's receiver IDs.
     *
     * Provide a map of receiver names, indexed by three-letter country code.
     * The library will attempt to use the proper receiver ID based on the
     * shipper country.
     *
     * @param array<string, string> $receiverIds
     *
     * @return ReturnLabelRequestBuilderInterface
     */
    public function setReceiverIds(array $receiverIds): ReturnLabelRequestBuilderInterface;

    /**
     * Set the receiver ID of the return shipment.
     *
     * The name of the return recipient (receiverId)
     * can be found in the DHL business customer portal.
     *
     * @param string $receiverId
     *
     * @return ReturnLabelRequestBuilderInterface
     */
    public function setReceiverId(string $receiverId): ReturnLabelRequestBuilderInterface;

    /**
     * Set customer reference (optional).
     *
     * The customer reference may be used to identify the original customer order.
     * It is visibly printed on the returns label.
     *
     * @param string $customerReference
     *
     * @return ReturnLabelRequestBuilderInterface
     */
    public function setCustomerReference(string $customerReference): ReturnLabelRequestBuilderInterface;

    /**
     * Set shipment reference (optional).
     *
     * The shipment reference may be used to identify the return shipment.
     * It is not visibly printed on the return label but only displayed
     * in the returns overview of the Post & DHL Business Customer Portal.
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
