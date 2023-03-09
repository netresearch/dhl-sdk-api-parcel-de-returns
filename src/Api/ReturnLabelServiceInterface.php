<?php

/**
 * See LICENSE.md for license details.
 */

declare(strict_types=1);

namespace Dhl\Sdk\ParcelDe\Returns\Api;

use Dhl\Sdk\ParcelDe\Returns\Api\Data\ConfirmationInterface;

/**
 * Interface ReturnLabelServiceInterface
 *
 * @api
 */
interface ReturnLabelServiceInterface
{
    /**
     * Create a return label.
     *
     * @param \JsonSerializable $returnOrder
     *
     * @return ConfirmationInterface
     */
    public function createReturnOrder(\JsonSerializable $returnOrder): ConfirmationInterface;
}
