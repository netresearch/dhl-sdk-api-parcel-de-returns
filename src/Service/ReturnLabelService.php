<?php

/**
 * See LICENSE.md for license details.
 */

declare(strict_types=1);

namespace Dhl\Sdk\ParcelDe\Returns\Service;

use Dhl\Sdk\ParcelDe\Returns\Api\Data\ConfirmationInterface;
use Dhl\Sdk\ParcelDe\Returns\Api\ReturnLabelServiceInterface;
use Dhl\Sdk\ParcelDe\Returns\Exception\ServiceExceptionFactory;
use Dhl\Sdk\ParcelDe\Returns\Model\ResponseMapper\ConfirmationResponseMapper;
use Dhl\Sdk\ParcelDe\Returns\Serializer\JsonSerializer;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;

class ReturnLabelService implements ReturnLabelServiceInterface
{
    private const OPERATION_BOOK_LABEL = 'orders';

    public function __construct(
        private readonly ClientInterface $client,
        private readonly string $baseUrl,
        private readonly JsonSerializer $serializer,
        private readonly ConfirmationResponseMapper $responseMapper,
        private readonly RequestFactoryInterface $requestFactory,
        private readonly StreamFactoryInterface $streamFactory,
    ) {
    }

    public function createReturnOrder(
        \JsonSerializable $returnOrder,
        string $labelType = self::LABEL_TYPE_BOTH
    ): ConfirmationInterface {
        $uri = sprintf('%s/%s?labelType=%s', $this->baseUrl, self::OPERATION_BOOK_LABEL, $labelType);

        try {
            $payload = $this->serializer->encode($returnOrder);
            $stream = $this->streamFactory->createStream($payload);

            $httpRequest = $this->requestFactory->createRequest('POST', $uri)->withBody($stream);

            $response = $this->client->sendRequest($httpRequest);
            $responseJson = (string) $response->getBody();

            return $this->responseMapper->map($this->serializer->decode($responseJson));
        } catch (ClientExceptionInterface $exception) {
            throw ServiceExceptionFactory::createServiceException($exception);
        } catch (\Throwable $exception) {
            throw ServiceExceptionFactory::create($exception);
        }
    }
}
