<?php

/**
 * See LICENSE.md for license details.
 */

declare(strict_types=1);

namespace Dhl\Sdk\ParcelDe\Returns\Model;

use Dhl\Sdk\ParcelDe\Returns\Exception\RequestValidatorException;

class ReturnLabelRequestValidator
{
    public const MSG_RECEIVER_ID_REQUIRED = 'Receiver ID is required.';
    public const MSG_RECEIVER_ID_INVALID = 'No Receiver ID found for country %s.';
    public const MSG_SHIPPER_ADDRESS_FIELD_REQUIRED = "'%s' is required for the shipper address.";
    public const MSG_COUNTRY_ISO_INVALID = 'Only ISO 3166-1 alpha-3 country codes are allowed, e.g. "DEU".';
    public const MSG_CURRENCY_INVALID = 'Only %s currency is allowed.';
    public const MSG_WEIGHT_UOM_INVALID = 'Only %s weight unit is allowed.';
    public const MSG_CUSTOMS_POSITIONS_COUNT = 'Between 1 and 20 customs items must be added.';
    public const MSG_CUSTOMS_POSITION_FIELD_REQUIRED = "'%s' is required for the customs item.";

    /**
     * @param string $weightUom
     * @param string[] $allowedUoms
     *
     * @throws RequestValidatorException
     */
    private static function validateWeightUom(string $weightUom, array $allowedUoms): void
    {
        if (!in_array($weightUom, $allowedUoms, true)) {
            throw new RequestValidatorException(sprintf(self::MSG_WEIGHT_UOM_INVALID, implode(', ', $allowedUoms)));
        }
    }

    /**
     * @param string $currency
     * @param string[] $allowedCurrencies
     *
     * @throws RequestValidatorException
     */
    private static function validateCurrency(string $currency, array $allowedCurrencies): void
    {
        if (!in_array($currency, $allowedCurrencies, true)) {
            throw new RequestValidatorException(sprintf(self::MSG_CURRENCY_INVALID, implode(', ', $allowedCurrencies)));
        }
    }

    /**
     * Validate request data before sending it to the web service.
     *
     * @param string[]|string[][][]|int[][][]|float[][][] $data
     * @param string[] $allowedCurrencies
     * @param string[] $allowedUoms
     *
     * @throws RequestValidatorException
     */
    public static function validate(array $data, array $allowedCurrencies, array $allowedUoms): void
    {
        if (!isset($data['receiverIds']) && !isset($data['receiverId'])) {
            throw new RequestValidatorException(self::MSG_RECEIVER_ID_REQUIRED);
        }

        if (isset($data['shipper'])) {
            foreach (['name', 'city', 'postalCode'] as $fieldName) {
                if (empty($data['shipper']['address'][$fieldName])) {
                    throw new RequestValidatorException(sprintf(self::MSG_SHIPPER_ADDRESS_FIELD_REQUIRED, $fieldName));
                }
            }

            $countryCode = (string) $data['shipper']['address']['countryCode'];
            if (strlen($countryCode) !== 3) {
                throw new RequestValidatorException(self::MSG_COUNTRY_ISO_INVALID);
            }

            if (empty($data['receiverId']) && !isset($data['receiverIds'][$countryCode])) {
                throw new RequestValidatorException(sprintf(self::MSG_RECEIVER_ID_INVALID, $countryCode));
            }
        }

        if (isset($data['package'], $data['package']['value'])) {
            self::validateCurrency((string) $data['package']['value']['currency'], $allowedCurrencies);
        }

        if (isset($data['package'], $data['package']['weight'])) {
            self::validateWeightUom((string) $data['package']['weight']['uom'], $allowedUoms);
        }

        if (!isset($data['customs'])) {
            // nothing more to validate
            return;
        }

        if (empty($data['customs']) || !is_array($data['customs']) || count($data['customs']) > 20) {
            throw new RequestValidatorException(self::MSG_CUSTOMS_POSITIONS_COUNT);
        }

        $itemRequired = ['description', 'qty', 'weight', 'value'];
        foreach ($data['customs'] as $customsItem) {
            foreach ($itemRequired as $fieldName) {
                if (empty($customsItem[$fieldName])) {
                    throw new RequestValidatorException(sprintf(self::MSG_CUSTOMS_POSITION_FIELD_REQUIRED, $fieldName));
                }
            }

            self::validateCurrency((string) $customsItem['currency'], $allowedCurrencies);
            self::validateWeightUom((string) $customsItem['weightUom'], $allowedUoms);

            if (!empty($customsItem['countryOfOrigin']) && strlen((string) $customsItem['countryOfOrigin']) !== 3) {
                throw new RequestValidatorException(self::MSG_COUNTRY_ISO_INVALID);
            }
        }
    }
}
