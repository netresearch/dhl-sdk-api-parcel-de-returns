<?php

/**
 * See LICENSE.md for license details.
 */

declare(strict_types=1);

namespace Dhl\Sdk\ParcelDe\Returns\Service\ReturnLabelService;

use Dhl\Sdk\ParcelDe\Returns\Api\Data\ConfirmationInterface;

class Confirmation implements ConfirmationInterface
{
    public function __construct(
        private readonly string $shipmentNumber,
        private readonly string $internationalShipmentNumber,
        private readonly string $routingCode,
        private readonly string $labelData,
        private readonly string $qrLabelData,
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

    public function getLabelData(): string
    {
        return $this->labelData;
    }

    public function getQrLabelData(): string
    {
        return $this->qrLabelData;
    }
}
