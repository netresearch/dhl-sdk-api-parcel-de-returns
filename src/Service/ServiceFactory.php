<?php

/**
 * See LICENSE.md for license details.
 */

declare(strict_types=1);

namespace Dhl\Sdk\ParcelDe\Returns\Service;

use Dhl\Sdk\ParcelDe\Returns\Api\Data\AuthenticationStorageInterface;
use Dhl\Sdk\ParcelDe\Returns\Api\ReturnLabelServiceInterface;
use Dhl\Sdk\ParcelDe\Returns\Api\ServiceFactoryInterface;
use Dhl\Sdk\ParcelDe\Returns\Exception\ServiceExceptionFactory;
use Dhl\Sdk\ParcelDe\Returns\Http\HttpServiceFactory;
use Http\Discovery\Exception\NotFoundException;
use Http\Discovery\HttpClientDiscovery;
use Psr\Log\LoggerInterface;

class ServiceFactory implements ServiceFactoryInterface
{
    public function createReturnLabelService(
        AuthenticationStorageInterface $authStorage,
        LoggerInterface $logger,
        bool $sandboxMode = false
    ): ReturnLabelServiceInterface {
        try {
            $httpClient = HttpClientDiscovery::find();
        } catch (NotFoundException $exception) {
            throw ServiceExceptionFactory::create($exception);
        }

        $httpServiceFactory = new HttpServiceFactory($httpClient);

        return $httpServiceFactory->createReturnLabelService($authStorage, $logger, $sandboxMode);
    }
}
