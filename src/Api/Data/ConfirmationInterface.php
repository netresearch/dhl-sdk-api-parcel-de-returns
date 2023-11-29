<?php

/**
 * See LICENSE.md for license details.
 */

declare(strict_types=1);

namespace Dhl\Sdk\ParcelDe\Returns\Api\Data;

/**
 * Interface ConfirmationInterface
 *
 * @api
 */
interface ConfirmationInterface
{
    /**
     * Obtain the shipment number of the created return label.
     */
    public function getShipmentNumber(): string;

    /**
     * Obtain the international shipment number of the created return label.
     */
    public function getInternationalShipmentNumber(): string;

    /**
     * Obtain the routing code of the created return label.
     */
    public function getRoutingCode(): string;

    /**
     * Obtain the base64 encoded labels and documents PDFs.
     */
    public function getLabelData(): string;

    /**
     * Obtain the base64 encoded labels and document images.
     */
    public function getQrLabelData(): string;
}
