<?php

/**
 * See LICENSE.md for license details.
 */

declare(strict_types=1);

namespace Dhl\Sdk\ParcelDe\Returns\Test\Expectation;

use Dhl\Sdk\ParcelDe\Returns\Api\Data\ConfirmationInterface;
use Dhl\Sdk\ParcelDe\Returns\Model\RequestType\ReturnOrder;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\ExpectationFailedException;
use Psr\Log\Test\TestLogger;

class ReturnLabelServiceTestExpectation
{
    /**
     * Assert that the properties in the request object match the serialized request payload.
     *
     * @param ReturnOrder $returnOrder The request object ready for serialization.
     * @param string $requestJson The actual message sent to the web service.
     */
    public static function assertLabelRequest(ReturnOrder $returnOrder, string $requestJson): void
    {
        $expected = json_decode(json_encode($returnOrder), true);
        $actual = json_decode($requestJson, true);

        Assert::assertSame($expected['receiverId'], $actual['receiverId']);
        Assert::assertSame($expected['customerReference'], $actual['customerReference']);

        $expectedShipper = $expected['shipper'];
        $actualShipper = $actual['shipper'];

        Assert::assertSame($expectedShipper['name1'], $actualShipper['name1']);
        Assert::assertSame($expectedShipper['addressStreet'], $actualShipper['addressStreet']);
        Assert::assertSame($expectedShipper['addressHouse'], $actualShipper['addressHouse']);
        Assert::assertSame($expectedShipper['postalCode'], $actualShipper['postalCode']);
        Assert::assertSame($expectedShipper['city'], $actualShipper['city']);
        Assert::assertSame($expectedShipper['country'], $actualShipper['country']);
        Assert::assertSame($expectedShipper['email'], $actualShipper['email']);

        if (isset($expected['customsDetails'])) {
            $expectedCustoms = $expected['customsDetails'];
            $actualCustoms = $actual['customsDetails'];

            Assert::assertSame($expectedCustoms['items'], $actualCustoms['items']);
        }
    }

    /**
     * Assert that the library's public API response object was properly generated from the response body.
     *
     * @param ConfirmationInterface $result
     * @param string $responseJson
     */
    public static function assertLabelResponse(ConfirmationInterface $result, string $responseJson): void
    {
        $responseData = json_decode($responseJson, true);

        Assert::assertNotEmpty($result->getShipmentNumber());
        Assert::assertNotEmpty($result->getRoutingCode());
        Assert::assertSame($responseData['shipmentNo'], $result->getShipmentNumber());
        Assert::assertSame($responseData['routingCode'], $result->getRoutingCode());

        Assert::assertSame($responseData['label']['b64'] ?? '', $result->getLabelData());
        Assert::assertSame($responseData['qrLabel']['b64'] ?? '', $result->getQrLabelData());
    }

    /**
     * Assert that logger contains an entry for client/server error (request level, HTTP status 400/500).
     *
     * @param TestLogger $logger Test logger.
     * @param string $responseBody Pre-recorded response.
     * @return void
     * @throws ExpectationFailedException
     */
    public static function assertErrorLogged(TestLogger $logger, string $responseBody = ''): void
    {
        Assert::assertTrue($logger->hasErrorRecords(), 'No error logged.');

        if ($responseBody) {
            $statusRegex = '|^HTTP/\d\.\d\s\d{3}\s[\w\s]+$|m';
            $hasResponseStatus = $logger->hasErrorThatMatches($statusRegex);
            Assert::assertTrue($hasResponseStatus, 'Logged messages do not contain response status code.');

            $hasResponse = $logger->hasErrorThatContains($responseBody);
            Assert::assertTrue($hasResponse, 'Error message not logged.');
        }
    }

    /**
     * Assert that logger contains records with HTTP status code and messages.
     *
     * @param TestLogger $logger Test logger.
     * @param string $requestBody Client's last request body.
     * @param string $responseJson Pre-recorded response.
     * @return void
     * @throws ExpectationFailedException
     */
    public static function assertCommunicationLogged(
        TestLogger $logger,
        string $requestBody,
        string $responseJson = ''
    ): void {
        Assert::assertTrue($logger->hasInfoRecords(), 'Logger has no info messages');

        $hasRequest = $logger->hasInfoThatContains($requestBody) || $logger->hasErrorThatContains($requestBody);
        Assert::assertTrue($hasRequest, 'Logged messages do not contain request');

        if ($responseJson) {
            $statusRegex = '|^HTTP/\d\.\d\s\d{3}\s[\w\s]+$|m';
            $hasStatusCode = $logger->hasInfoThatMatches($statusRegex) || $logger->hasErrorThatMatches($statusRegex);
            Assert::assertTrue($hasStatusCode, 'Logged messages do not contain status code.');

            $hasResponse = $logger->hasInfoThatContains($responseJson) || $logger->hasErrorThatContains($responseJson);
            Assert::assertTrue($hasResponse, 'Logged messages do not contain response');
        }
    }
}
