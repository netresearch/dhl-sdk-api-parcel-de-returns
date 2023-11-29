<?php

/**
 * See LICENSE.md for license details.
 */

declare(strict_types=1);

namespace Dhl\Sdk\ParcelDe\Returns\Model\ResponseType;

class ReturnOrderConfirmation
{
    /**
     * Standard elements for JSON status.
     *
     * @link https://tools.ietf.org/html/rfc7807
     */
    private Status $status;

    private string $shipmentNo;

    private ?string $internationalShipmentNo = null;

    /**
     * Encoded document. All types of labels and documents.
     */
    private ?Label $label = null;

    /**
     * Encoded document. All types of labels and documents.
     */
    private ?Label $qrLabel = null;

    /**
     * Routing code of the return label.
     */
    private string $routingCode;

    public function getStatus(): Status
    {
        return $this->status;
    }

    public function getShipmentNo(): string
    {
        return $this->shipmentNo;
    }

    public function getInternationalShipmentNo(): ?string
    {
        return $this->internationalShipmentNo;
    }

    public function getLabel(): ?Label
    {
        return $this->label;
    }

    public function getQrLabel(): ?Label
    {
        return $this->qrLabel;
    }

    public function getRoutingCode(): string
    {
        return $this->routingCode;
    }
}
