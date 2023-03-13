<?php

/**
 * See LICENSE.md for license details.
 */

declare(strict_types=1);

namespace Dhl\Sdk\ParcelDe\Returns\Test\Provider;

use Dhl\Sdk\ParcelDe\Returns\Exception\RequestValidatorException;
use Dhl\Sdk\ParcelDe\Returns\Model\ReturnLabelRequestBuilder;

class ReturnLabelMessageProvider
{
    private const RECEIVER_IDS = [
        'BEL' => 'bel',
        'BGR' => 'bgr',
        'DNK' => 'dnk',
        'DEU' => 'deu',
        'NLD' => 'nld',
        'CHE' => 'che',
    ];

    /**
     * @throws RequestValidatorException
     */
    public static function domesticRequest(): \JsonSerializable
    {
        $requestBuilder = new ReturnLabelRequestBuilder();
        $requestBuilder->setReceiverIds(self::RECEIVER_IDS);
        $requestBuilder->setCustomerReference('22222222220701');
        $requestBuilder->setShipper('Jane Doe', 'DEU', '53113', 'Bonn', 'Sträßchensweg', '2');
        $requestBuilder->setShipperContact('tester@nettest.eu');

        return $requestBuilder->create();
    }

    /**
     * @throws RequestValidatorException
     */
    public static function domesticRequestInvalidPostcode(): \JsonSerializable
    {
        $requestBuilder = new ReturnLabelRequestBuilder();
        $requestBuilder->setReceiverIds(self::RECEIVER_IDS);
        $requestBuilder->setCustomerReference('22222222220701');
        $requestBuilder->setShipper('Jane Doe', 'DEU', '5300', 'Bonn', 'Sträßchensweg', '2');
        $requestBuilder->setShipperContact('tester@nettest.eu');

        return $requestBuilder->create();
    }

    public static function domesticResponse(): string
    {
        return \file_get_contents(__DIR__ . '/_files/returnLabelDomesticBoth.json');
    }

    public static function domesticResponsePdf(): string
    {
        return \file_get_contents(__DIR__ . '/_files/returnLabelDomesticPdf.json');
    }

    public static function domesticResponseQr(): string
    {
        return \file_get_contents(__DIR__ . '/_files/returnLabelDomesticQr.json');
    }

    /**
     * @throws RequestValidatorException
     */
    public static function euRequest(): \JsonSerializable
    {
        $requestBuilder = new ReturnLabelRequestBuilder();
        $requestBuilder->setReceiverIds(self::RECEIVER_IDS);
        $requestBuilder->setCustomerReference('22222222220753');
        $requestBuilder->setShipper(
            'Jane Doe',
            'NLD',
            '1013 AA',
            'Amsterdam',
            'De Ruyterkade',
            '5',
            'Amsterdam Tourist & Convention Board'
        );
        $requestBuilder->setShipperContact('tester@nettest.eu');

        return $requestBuilder->create();
    }

    public static function euResponse(): string
    {
        return \file_get_contents(__DIR__ . '/_files/returnLabelEu.json');
    }

    /**
     * @throws RequestValidatorException
     */
    public static function customsRequest(): \JsonSerializable
    {
        $requestBuilder = new ReturnLabelRequestBuilder();
        $requestBuilder->setReceiverIds(self::RECEIVER_IDS);
        $requestBuilder->setCustomerReference('22222222220753');
        $requestBuilder->setShipper('Test Tester', 'CHE', '8005', 'Zürich', 'Lagerstrasse', '10');
        $requestBuilder->setShipperContact('tester@nettest.eu');
        $requestBuilder->addCustomsItem(2, 'Foo Bar', 96.98, 'EUR', 1.8, 'kg', 'POL', '61112090');

        return $requestBuilder->create();
    }

    public static function customsResponse(): string
    {
        return \file_get_contents(__DIR__ . '/_files/returnLabelCustoms.json');
    }

    public static function unauthorizedAppResponse(): string
    {
        return \file_get_contents(__DIR__ . '/_files/invalidAppToken.json');
    }

    public static function unauthorizedUserResponse(): string
    {
        return \file_get_contents(__DIR__ . '/_files/invalidUser.json');
    }

    public static function invalidPostcodeResponse(): string
    {
        return \file_get_contents(__DIR__ . '/_files/invalidPostcode.json');
    }
}
