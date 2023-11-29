<?php

/**
 * See LICENSE.md for license details.
 */

declare(strict_types=1);

namespace Dhl\Sdk\ParcelDe\Returns\Model\RequestType;

class ReturnOrder implements \JsonSerializable
{
    /**
     * The customer reference may be used to identify the original customer order.
     *
     * It is visibly printed on the returns label.
     */
    private ?string $customerReference = null;

    /**
     * The shipment reference may be used to identify the return shipment.
     *
     * It is not visibly printed on the returns label but only displayed
     * in the returns overview of the Post & DHL Business Customer Portal.
     */
    private ?string $shipmentReference = null;

    private ?Shipper $shipper = null;

    private ?ItemWeight $itemWeight = null;

    /**
     * Currency and numeric value.
     */
    private ?ItemValue $itemValue = null;

    /**
     * A customs form (CN23) and the respective data transmission is mandatory
     * for returns with the DHL Retoure International from outside the European Union
     * to facilitate customs processes. Please refer to the DHL Retoure International
     * product information for an overview of the origin country specifics.
     */
    private ?CustomsDetails $customsDetails = null;

    /**
     * @param string $receiverId The receiver id of the return shipment
     */
    public function __construct(private readonly string $receiverId)
    {
    }

    public function setCustomerReference(?string $customerReference): self
    {
        $this->customerReference = $customerReference;

        return $this;
    }

    public function setShipmentReference(?string $shipmentReference): self
    {
        $this->shipmentReference = $shipmentReference;

        return $this;
    }

    public function setShipper(?Shipper $shipper): void
    {
        $this->shipper = $shipper;
    }

    public function setItemWeight(?ItemWeight $itemWeight): void
    {
        $this->itemWeight = $itemWeight;
    }

    public function setItemValue(?ItemValue $itemValue): void
    {
        $this->itemValue = $itemValue;
    }

    public function setCustomsDetails(?CustomsDetails $customsDetails): void
    {
        $this->customsDetails = $customsDetails;
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
