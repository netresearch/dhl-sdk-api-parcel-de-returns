<?php

/**
 * See LICENSE.md for license details.
 */

declare(strict_types=1);

namespace Dhl\Sdk\ParcelDe\Returns\Api;

use Dhl\Sdk\ParcelDe\Returns\Api\Data\ConfirmationInterface;
use Dhl\Sdk\ParcelDe\Returns\Exception\ServiceException;

/**
 * Interface ReturnLabelServiceInterface
 *
 * @api
 */
interface ReturnLabelServiceInterface
{
    public const LABEL_TYPE_PDF = 'SHIPMENT_LABEL';
    public const LABEL_TYPE_QR = 'QR_LABEL';
    public const LABEL_TYPE_BOTH = 'BOTH';

    /**
     * Create a return label.
     *
     * @param \JsonSerializable $returnOrder Details of the return label that should be created.
     * @param string $labelType Controls which documents are returned.
     *
     * @return ConfirmationInterface
     * @throws ServiceException
     */
    public function createReturnOrder(
        \JsonSerializable $returnOrder,
        string $labelType = self::LABEL_TYPE_BOTH
    ): ConfirmationInterface;
}
