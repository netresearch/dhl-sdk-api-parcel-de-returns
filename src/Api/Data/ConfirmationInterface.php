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
     *
     * @return string
     */
    public function getShipmentNumber(): string;

    /**
     * Obtain the base64 encoded label PDF binary.
     *
     * @return string
     */
    public function getLabelData(): string;

    /**
     * Obtain the base64 encoded QR code PNG binary.
     *
     * @return string
     */
    public function getQrLabelData(): string;

    /**
     * Obtain the routing code of the created return label.
     *
     * @return string
     */
    public function getRoutingCode(): string;
}
