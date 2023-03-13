<?php

/**
 * See LICENSE.md for license details.
 */

declare(strict_types=1);

namespace Dhl\Sdk\ParcelDe\Returns\Model\ResponseMapper;

use Dhl\Sdk\ParcelDe\Returns\Model\ResponseType\ReturnOrderConfirmation;
use Dhl\Sdk\ParcelDe\Returns\Service\ReturnLabelService\Confirmation;

class ConfirmationResponseMapper
{
    public function map(ReturnOrderConfirmation $confirmation): Confirmation
    {
        return new Confirmation(
            $confirmation->getShipmentNo(),
            $confirmation->getInternationalShipmentNo() ?: '',
            $confirmation->getRoutingCode(),
            $confirmation->getLabel() ? $confirmation->getLabel()->getB64() : '',
            $confirmation->getQrLabel() ? $confirmation->getQrLabel()->getB64() : '',
        );
    }
}
