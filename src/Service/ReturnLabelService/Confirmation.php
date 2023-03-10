<?php

/**
 * See LICENSE.md for license details.
 */

declare(strict_types=1);

namespace Dhl\Sdk\ParcelDe\Returns\Service\ReturnLabelService;

use Dhl\Sdk\ParcelDe\Returns\Api\Data\ConfirmationInterface;

class Confirmation implements ConfirmationInterface
{
    /**
     * @param string $shipmentNumber
     * @param string $internationalShipmentNumber
     * @param string $routingCode
     * @param string[] $documents
     * @param string[] $qrDocuments
     */
    public function __construct(
        private readonly string $shipmentNumber,
        private readonly string $internationalShipmentNumber,
        private readonly string $routingCode,
        private readonly array $documents,
        private readonly array $qrDocuments,
    ) {
    }

    public function getShipmentNumber(): string
    {
        return $this->shipmentNumber;
    }

    public function getInternationalShipmentNumber(): string
    {
        return $this->internationalShipmentNumber;
    }

    public function getRoutingCode(): string
    {
        return $this->routingCode;
    }

    public function getDocuments(): array
    {
        return $this->documents;
    }

    public function getQrDocuments(): array
    {
        return $this->qrDocuments;
    }
}
