<?php

/**
 * See LICENSE.md for license details.
 */

declare(strict_types=1);

namespace Dhl\Sdk\ParcelDe\Returns\Http;

use Dhl\Sdk\ParcelDe\Returns\Api\Data\AuthenticationStorageInterface;
use Dhl\Sdk\ParcelDe\Returns\Api\ReturnLabelServiceInterface;
use Dhl\Sdk\ParcelDe\Returns\Api\ServiceFactoryInterface;
use Dhl\Sdk\ParcelDe\Returns\Exception\ServiceExceptionFactory;
use Dhl\Sdk\ParcelDe\Returns\Model\ResponseMapper\ConfirmationResponseMapper;
use Dhl\Sdk\ParcelDe\Returns\Serializer\JsonSerializer;
use Dhl\Sdk\ParcelDe\Returns\Service\ReturnLabelService;
use Http\Client\Common\Plugin\AuthenticationPlugin;
use Http\Client\Common\Plugin\ContentLengthPlugin;
use Http\Client\Common\Plugin\HeaderDefaultsPlugin;
use Http\Client\Common\Plugin\LoggerPlugin;
use Http\Client\Common\PluginClient;
use Http\Discovery\Exception\NotFoundException;
use Http\Discovery\Psr17FactoryDiscovery;
use Http\Message\Authentication\BasicAuth;
use Http\Message\Formatter\FullHttpMessageFormatter;
use Psr\Http\Client\ClientInterface;
use Psr\Log\LoggerInterface;

/**
 * Create a service instance for REST web service communication.
 */
class HttpServiceFactory implements ServiceFactoryInterface
{
    public function __construct(private readonly ClientInterface $httpClient)
    {
    }

    public function createReturnLabelService(
        AuthenticationStorageInterface $authStorage,
        LoggerInterface $logger,
        bool $sandboxMode = false
    ): ReturnLabelServiceInterface {
        $appAuth = new BasicAuth($authStorage->getUser(), $authStorage->getPassword());
        $headers = [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'dhl-api-key' => $authStorage->getApiKey(),
        ];

        $client = new PluginClient(
            $this->httpClient,
            [
                new HeaderDefaultsPlugin($headers),
                new AuthenticationPlugin($appAuth),
                new ContentLengthPlugin(),
                new LoggerPlugin($logger, new FullHttpMessageFormatter(null)),
            ]
        );

        try {
            $requestFactory = Psr17FactoryDiscovery::findRequestFactory();
            $streamFactory = Psr17FactoryDiscovery::findStreamFactory();
        } catch (NotFoundException $exception) {
            throw ServiceExceptionFactory::create($exception);
        }

        return new ReturnLabelService(
            $client,
            $sandboxMode ? self::BASE_URL_SANDBOX : self::BASE_URL_PRODUCTION,
            new JsonSerializer(),
            new ConfirmationResponseMapper(),
            $requestFactory,
            $streamFactory
        );
    }
}
