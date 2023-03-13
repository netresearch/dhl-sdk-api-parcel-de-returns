<?php

/**
 * See LICENSE.md for license details.
 */

declare(strict_types=1);

namespace Dhl\Sdk\ParcelDe\Returns\Service;

use Dhl\Sdk\ParcelDe\Returns\Api\Data\AuthenticationStorageInterface;
use Dhl\Sdk\ParcelDe\Returns\Exception\AuthenticationException;
use Dhl\Sdk\ParcelDe\Returns\Exception\DetailedServiceException;
use Dhl\Sdk\ParcelDe\Returns\Exception\RequestValidatorException;
use Dhl\Sdk\ParcelDe\Returns\Exception\ServiceException;
use Dhl\Sdk\ParcelDe\Returns\Http\HttpServiceFactory;
use Dhl\Sdk\ParcelDe\Returns\Model\RequestType\ReturnOrder;
use Dhl\Sdk\ParcelDe\Returns\Test\Expectation\ReturnLabelServiceTestExpectation as Expectation;
use Dhl\Sdk\ParcelDe\Returns\Test\Provider\ReturnLabelServiceTestProvider;
use Http\Discovery\Psr17FactoryDiscovery;
use Http\Mock\Client;
use PHPUnit\Framework\TestCase;
use Psr\Log\Test\TestLogger;

class ReturnLabelServiceTest extends TestCase
{
    /**
     * @return \JsonSerializable[][]|string[][]
     * @throws RequestValidatorException
     */
    public function successDataProvider(): array
    {
        return ReturnLabelServiceTestProvider::labelSuccess();
    }

    /**
     * @return \JsonSerializable[][]|string[][]
     * @throws RequestValidatorException
     */
    public function unauthorizedDataProvider(): array
    {
        return ReturnLabelServiceTestProvider::unauthorizedError();
    }

    /**
     * @return \JsonSerializable[][]|string[][]
     * @throws RequestValidatorException
     */
    public function badRequestDataProvider(): array
    {
        return ReturnLabelServiceTestProvider::badRequestError();
    }

    /**
     * @test
     * @dataProvider successDataProvider
     * @throws ServiceException
     */
    public function bookLabelSuccess(
        AuthenticationStorageInterface $authStorage,
        ReturnOrder $returnOrder,
        string $labelType,
        string $responseBody
    ) {
        $httpClient = new Client();

        $responseFactory = Psr17FactoryDiscovery::findResponseFactory();
        $streamFactory = Psr17FactoryDiscovery::findStreamFactory();

        $returnLabelResponse = $responseFactory
            ->createResponse(201, 'Created')
            ->withHeader('Content-Type', 'application/json')
            ->withBody($streamFactory->createStream($responseBody));

        $httpClient->setDefaultResponse($returnLabelResponse);
        $logger = new TestLogger();
        $serviceFactory = new HttpServiceFactory($httpClient);
        $service = $serviceFactory->createReturnLabelService($authStorage, $logger, true);
        $confirmation = $service->createReturnOrder($returnOrder, $labelType);

        $lastRequest = $httpClient->getLastRequest();
        $requestBody = (string)$lastRequest->getBody();

        Expectation::assertLabelRequest($returnOrder, $requestBody);
        Expectation::assertLabelResponse($confirmation, $responseBody);
    }

    /**
     * @test
     * @dataProvider unauthorizedDataProvider
     * @throws ServiceException
     */
    public function unauthorized(
        AuthenticationStorageInterface $authStorage,
        ReturnOrder $returnOrder,
        string $labelType,
        string $responseBody
    ) {
        $this->expectExceptionCode(401);
        $this->expectException(AuthenticationException::class);

        $httpClient = new Client();

        $responseFactory = Psr17FactoryDiscovery::findResponseFactory();
        $streamFactory = Psr17FactoryDiscovery::findStreamFactory();

        $returnLabelResponse = $responseFactory
            ->createResponse(401, 'Unauthorized')
            ->withHeader('Content-Type', 'application/json')
            ->withBody($streamFactory->createStream($responseBody));

        $httpClient->setDefaultResponse($returnLabelResponse);
        $logger = new TestLogger();
        $serviceFactory = new HttpServiceFactory($httpClient);
        $service = $serviceFactory->createReturnLabelService($authStorage, $logger, true);

        try {
            $service->createReturnOrder($returnOrder, $labelType);
        } catch (ServiceException $exception) {
            $lastRequest = $httpClient->getLastRequest();
            $requestBody = (string) $lastRequest->getBody();

            Expectation::assertErrorLogged($logger, $responseBody);
            Expectation::assertCommunicationLogged($logger, $requestBody, $responseBody);

            throw $exception;
        }
    }

    /**
     * @test
     * @dataProvider badRequestDataProvider
     * @throws ServiceException
     */
    public function badRequest(
        AuthenticationStorageInterface $authStorage,
        ReturnOrder $returnOrder,
        string $labelType,
        string $responseBody
    ) {
        $this->expectExceptionCode(400);
        $this->expectException(DetailedServiceException::class);

        $httpClient = new Client();

        $responseFactory = Psr17FactoryDiscovery::findResponseFactory();
        $streamFactory = Psr17FactoryDiscovery::findStreamFactory();

        $returnLabelResponse = $responseFactory
            ->createResponse(400, 'Bad Request')
            ->withHeader('Content-Type', 'application/json')
            ->withBody($streamFactory->createStream($responseBody));

        $httpClient->setDefaultResponse($returnLabelResponse);
        $logger = new TestLogger();
        $serviceFactory = new HttpServiceFactory($httpClient);
        $service = $serviceFactory->createReturnLabelService($authStorage, $logger, true);

        try {
            $service->createReturnOrder($returnOrder, $labelType);
        } catch (ServiceException $exception) {
            $lastRequest = $httpClient->getLastRequest();
            $requestBody = (string) $lastRequest->getBody();

            Expectation::assertErrorLogged($logger, $responseBody);
            Expectation::assertCommunicationLogged($logger, $requestBody, $responseBody);

            throw $exception;
        }
    }
}
