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
     * @var Status
     */
    private Status $status;

    /**
     * @var string
     */
    private string $shipmentNo;

    /**
     * @var string|null
     */
    private ?string $internationalShipmentNo;

    /**
     * Encoded document. All types of labels and documents.
     *
     * @var Label[]
     */
    private array $label;

    /**
     * Encoded document. All types of labels and documents.
     *
     * @var Label[]|null
     */
    private ?array $qrLabel;

    /**
     * Routing code of the return label.
     *
     * @var string
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

    /**
     * @return Label[]
     */
    public function getLabel(): array
    {
        return $this->label;
    }

    /**
     * @return Label[]
     */
    public function getQrLabel(): array
    {
        if (!is_array($this->qrLabel)) {
            return [];
        }

        return $this->qrLabel;
    }

    public function getRoutingCode(): string
    {
        return $this->routingCode;
    }
}
