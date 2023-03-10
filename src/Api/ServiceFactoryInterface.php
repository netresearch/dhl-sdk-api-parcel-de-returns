<?php

/**
 * See LICENSE.md for license details.
 */

declare(strict_types=1);

namespace Dhl\Sdk\ParcelDe\Returns\Api;

use Dhl\Sdk\ParcelDe\Returns\Api\Data\AuthenticationStorageInterface;
use Dhl\Sdk\ParcelDe\Returns\Exception\ServiceException;
use Psr\Log\LoggerInterface;

/**
 * Interface ServiceFactoryInterface
 *
 * @api
 */
interface ServiceFactoryInterface
{
    public const BASE_URL_PRODUCTION = 'https://api-eu.dhl.com/parcel/de/shipping/returns/v1';
    public const BASE_URL_SANDBOX = 'https://api-sandbox.dhl.com/parcel/de/shipping/returns/v1';

    /**
     * Create the service able to perform return shipment label requests.
     *
     * @param AuthenticationStorageInterface $authStorage
     * @param LoggerInterface $logger
     * @param bool $sandboxMode
     *
     * @return ReturnLabelServiceInterface
     *
     * @throws ServiceException
     */
    public function createReturnLabelService(
        AuthenticationStorageInterface $authStorage,
        LoggerInterface $logger,
        bool $sandboxMode = false
    ): ReturnLabelServiceInterface;
}
