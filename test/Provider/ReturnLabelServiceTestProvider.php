<?php

/**
 * See LICENSE.md for license details.
 */

declare(strict_types=1);

namespace Dhl\Sdk\ParcelDe\Returns\Test\Provider;

use Dhl\Sdk\ParcelDe\Returns\Api\ReturnLabelServiceInterface;
use Dhl\Sdk\ParcelDe\Returns\Auth\AuthenticationStorage;
use Dhl\Sdk\ParcelDe\Returns\Exception\RequestValidatorException;

class ReturnLabelServiceTestProvider
{
    /**
     * @throws RequestValidatorException
     */
    public static function labelSuccess(): array
    {
        $authStorage = new AuthenticationStorage('4pp-t0k3N', 'u53R', 'p455w0rD');

        return [
            'domestic request with pdf and qr' => [
                $authStorage,
                ReturnLabelMessageProvider::domesticRequest(),
                ReturnLabelServiceInterface::LABEL_TYPE_BOTH,
                ReturnLabelMessageProvider::domesticResponse(),
            ],
            'domestic request with pdf' => [
                $authStorage,
                ReturnLabelMessageProvider::domesticRequest(),
                ReturnLabelServiceInterface::LABEL_TYPE_PDF,
                ReturnLabelMessageProvider::domesticResponsePdf(),
            ],
            'domestic request with qr' => [
                $authStorage,
                ReturnLabelMessageProvider::domesticRequest(),
                ReturnLabelServiceInterface::LABEL_TYPE_QR,
                ReturnLabelMessageProvider::domesticResponseQr(),
            ],
            'eu request' => [
                $authStorage,
                ReturnLabelMessageProvider::euRequest(),
                ReturnLabelServiceInterface::LABEL_TYPE_BOTH,
                ReturnLabelMessageProvider::euResponse(),
            ],
            'non-eu request' => [
                $authStorage,
                ReturnLabelMessageProvider::customsRequest(),
                ReturnLabelServiceInterface::LABEL_TYPE_BOTH,
                ReturnLabelMessageProvider::customsResponse(),
            ],
        ];
    }

    /**
     * @throws RequestValidatorException
     */
    public static function unauthorizedError(): array
    {
        return [
            'invalid app token' => [
                new AuthenticationStorage('4pp-1nv4L1d', 'u53R', 'p455w0rD'),
                ReturnLabelMessageProvider::domesticRequest(),
                ReturnLabelServiceInterface::LABEL_TYPE_PDF,
                ReturnLabelMessageProvider::unauthorizedAppResponse(),
            ],
            'invalid user auth' => [
                new AuthenticationStorage('4pp-t0k3N', 'u53R', 'p455w0rD-1nv4L1d'),
                ReturnLabelMessageProvider::domesticRequest(),
                ReturnLabelServiceInterface::LABEL_TYPE_PDF,
                ReturnLabelMessageProvider::unauthorizedUserResponse(),
            ],
        ];
    }

    /**
     * @throws RequestValidatorException
     */
    public static function badRequestError(): array
    {
        $authStorage = new AuthenticationStorage('4pp-t0k3N', 'u53R', 'p455w0rD');

        return [
            'invalid postcode' => [
                $authStorage,
                ReturnLabelMessageProvider::domesticRequestInvalidPostcode(),
                ReturnLabelServiceInterface::LABEL_TYPE_PDF,
                ReturnLabelMessageProvider::invalidPostcodeResponse(),
            ],
        ];
    }
}
