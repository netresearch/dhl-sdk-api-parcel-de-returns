<?php

/**
 * See LICENSE.md for license details.
 */

declare(strict_types=1);

namespace Dhl\Sdk\ParcelDe\Returns\Model;

use Dhl\Sdk\ParcelDe\Returns\Api\ReturnLabelRequestBuilderInterface;
use Dhl\Sdk\ParcelDe\Returns\Model\RequestType\CustomsDetails;
use Dhl\Sdk\ParcelDe\Returns\Model\RequestType\CustomsItem;
use Dhl\Sdk\ParcelDe\Returns\Model\RequestType\ItemValue;
use Dhl\Sdk\ParcelDe\Returns\Model\RequestType\ItemWeight;
use Dhl\Sdk\ParcelDe\Returns\Model\RequestType\ReturnOrder;
use Dhl\Sdk\ParcelDe\Returns\Model\RequestType\Shipper;

class ReturnLabelRequestBuilder implements ReturnLabelRequestBuilderInterface
{
    /**
     * @var mixed[]
     */
    private array $data = [];

    public function setAccountDetails(
        string $receiverId,
        ?string $customerReference = null
    ): ReturnLabelRequestBuilderInterface {
        $this->data['receiverId'] = $receiverId;
        $this->data['customerReference'] = $customerReference;

        return $this;
    }

    public function setShipmentReference(string $shipmentReference): ReturnLabelRequestBuilderInterface
    {
        $this->data['shipmentReference'] = $shipmentReference;

        return $this;
    }

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
    ): ReturnLabelRequestBuilderInterface {
        $this->data['shipper']['address']['name'] = $name;
        $this->data['shipper']['address']['countryCode'] = $countryCode;
        $this->data['shipper']['address']['postalCode'] = $postalCode;
        $this->data['shipper']['address']['city'] = $city;
        $this->data['shipper']['address']['streetName'] = $streetName;
        $this->data['shipper']['address']['streetNumber'] = $streetNumber;
        $this->data['shipper']['address']['company'] = $company;
        $this->data['shipper']['address']['nameAddition'] = $nameAddition;
        $this->data['shipper']['address']['state'] = $state;
        $this->data['shipper']['address']['streetAddition'] = $streetAddition;

        return $this;
    }

    public function setShipperContact(string $email, ?string $phone = null): ReturnLabelRequestBuilderInterface
    {
        $this->data['shipper']['contact']['email'] = $email;
        $this->data['shipper']['contact']['phone'] = $phone;

        return $this;
    }

    public function setPackageWeight(float $value, string $uom = self::WEIGHT_KG): ReturnLabelRequestBuilderInterface
    {
        $this->data['package']['weight']['value'] = $value;
        $this->data['package']['weight']['uom'] = $uom;

        return $this;
    }

    public function setPackageValue(
        float $value,
        string $currency = self::CURRENCY_EUR
    ): ReturnLabelRequestBuilderInterface {
        $this->data['package']['value']['value'] = $value;
        $this->data['package']['value']['currency'] = $currency;

        return $this;
    }

    public function addCustomsItem(
        int $qty,
        string $description,
        float $value,
        string $currency,
        float $weight,
        string $weightUom,
        ?string $countryOfOrigin = null,
        ?string $hsCode = null
    ): ReturnLabelRequestBuilderInterface {
        $this->data['customs'][] = [
            'qty' => $qty,
            'description' => $description,
            'value' => $value,
            'currency' => strtoupper($currency),
            'weight' => $weight,
            'weightUom' => strtolower($weightUom),
            'countryOfOrigin' => $countryOfOrigin,
            'hsCode' => $hsCode
        ];

        return $this;
    }

    public function create(): \JsonSerializable
    {
        ReturnLabelRequestValidator::validate(
            $this->data,
            [
                self::CURRENCY_EUR,
                self::CURRENCY_USD,
                self::CURRENCY_CZK,
                self::CURRENCY_GBP,
                self::CURRENCY_CHF,
                self::CURRENCY_SGD,
            ],
            [
                self::WEIGHT_G,
                self::WEIGHT_KG,
            ]
        );

        $returnOrder = new ReturnOrder($this->data['receiverId']);
        $returnOrder->setCustomerReference($this->data['customerReference']);
        $returnOrder->setShipmentReference($this->data['shipmentReference'] ?? null);

        if (isset($this->data['shipper'])) {
            $shipper = new Shipper(
                $this->data['shipper']['address']['name'],
                $this->data['shipper']['address']['city'],
                $this->data['shipper']['address']['postalCode']
            );

            $shipper->setName2($this->data['shipper']['address']['company']);
            $shipper->setName3($this->data['shipper']['address']['nameAddition']);

            $shipper->setAddressStreet($this->data['shipper']['address']['streetName']);
            $shipper->setAddressHouse($this->data['shipper']['address']['streetNumber']);

            if (is_array($this->data['shipper']['address']['streetAddition'])) {
                $streetAddition = array_filter($this->data['shipper']['address']['streetAddition']);
                $shipper->setAdditionalAddressInformation1($streetAddition[0] ?? null);
                $shipper->setAdditionalAddressInformation2($streetAddition[1] ?? null);
            }

            $shipper->setCountry($this->data['shipper']['address']['countryCode']);
            $shipper->setState($this->data['shipper']['address']['state']);
            $shipper->setEmail($this->data['shipper']['contact']['email'] ?? null);
            $shipper->setPhone($this->data['shipper']['contact']['phone'] ?? null);

            $returnOrder->setShipper($shipper);
        }

        if (isset($this->data['package']['weight'])) {
            $itemWeight = new ItemWeight(
                $this->data['package']['weight']['uom'],
                $this->data['package']['weight']['value']
            );

            $returnOrder->setItemWeight($itemWeight);
        }

        if (isset($this->data['package']['value'])) {
            $itemValue = new ItemValue(
                $this->data['package']['value']['currency'],
                $this->data['package']['value']['value']
            );

            $returnOrder->setItemValue($itemValue);
        }

        if (isset($this->data['customs'])) {
            $positions = array_map(
                static function (array $itemData) {
                    $position = new CustomsItem(
                        $itemData['description'],
                        $itemData['qty'],
                        new ItemWeight($itemData['weightUom'], $itemData['weight']),
                        new ItemValue($itemData['currency'], $itemData['value'])
                    );

                    $position->setCountryOfOrigin($itemData['countryOfOrigin']);
                    $position->setHsCode($itemData['hsCode']);

                    return $position;
                },
                $this->data['customs']
            );

            $customsDetails = new CustomsDetails($positions);
            $returnOrder->setCustomsDetails($customsDetails);
        }

        $this->data = [];

        return $returnOrder;
    }
}
