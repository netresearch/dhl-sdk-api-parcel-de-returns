# DHL Parcel DE Returns API SDK

The DHL Parcel DE Returns API SDK package offers an interface to the following web services:

- [Returns API](https://developer.dhl.com/api-reference/dhl-parcel-de-returns-post-parcel-germany)

## Requirements

### System Requirements

- PHP 8.1+ with JSON extension

### Package Requirements

- `netresearch/jsonmapper`: Mapper for deserialization of JSON response messages into PHP objects
- `php-http/discovery`: Discovery service for HTTP client and message factory implementations
- `php-http/httplug`: Pluggable HTTP client abstraction
- `php-http/logger-plugin`: HTTP client logger plugin for HTTPlug
- `psr/http-client`: PSR-18 HTTP client interfaces
- `psr/http-factory`: PSR-7 HTTP message factory interfaces
- `psr/http-message`: PSR-7 HTTP message interfaces
- `psr/log`: PSR-3 logger interfaces

### Virtual Package Requirements

- `psr/http-client-implementation`: Any package that provides a PSR-18 compatible HTTP client
- `psr/http-factory-implementation`: Any package that provides PSR-7 compatible HTTP message factories
- `psr/http-message-implementation`: Any package that provides PSR-7 HTTP messages

### Development Package Requirements

- `nyholm/psr7`: PSR-7 HTTP message factory & message implementation
- `phpunit/phpunit`: Testing framework
- `php-http/mock-client`: HTTPlug mock client implementation
- `phpstan/phpstan`: Static analysis tool
- `fig/log-test`: PSR-3 logger implementation for testing purposes
- `squizlabs/php_codesniffer`: Static analysis tool

## Installation

```bash
composer require dhl/sdk-api-parcel-de-returns
```

## Uninstallation

```bash
composer remove dhl/sdk-api-parcel-de-returns
```

## Testing

```bash
./vendor/bin/phpunit -c test/phpunit.xml
```

## Features

The DHL Parcel DE Returns API SDK supports the following features:

* Book return labels ([`POST /orders`](https://developer.dhl.com/api-reference/dhl-parcel-de-returns-post-parcel-germany#operations-Orders-createReturnOrder))

### Authentication

The DHL Parcel DE Returns API requires a two-level authentication
(see [API User Guide](https://developer.dhl.com/api-reference/dhl-parcel-de-returns-post-parcel-germany#get-started-section/user-guide)):

1. The **application** submits a _Consumer Key Header_ ("API Key") that must be
   created in the [DHL API Developer Portal](https://developer.dhl.com/user/apps).
2. The **user** is identified via _HTTP Basic Authentication_ with credentials
   configured in the [DHL Business Customer Portal](https://geschaeftskunden.dhl.de/).

These credentials are passed to the SDK via `\Dhl\Sdk\ParcelDe\Returns\Api\Data\AuthenticationStorageInterface`.
Use the default implementation or create your own.

### Book Return Label

Create a return label PDF, or a QR code to be scanned by a place of committal
(e.g. post office). For return shipments from outside the EU, a customs document
can also be requested.

The destination address of the return shipment is determined via _Receiver ID_
parameter. Return recipients and their ID ("Return recipient's name") are
configured in the [DHL Business Customer Portal](https://geschaeftskunden.dhl.de/),
_Returns Settings_ section. More detailed information can be found in the
[API FAQ](https://developer.dhl.com/api-reference/dhl-parcel-de-returns-post-parcel-germany#additional-information-section/faq--returns-recipients).

#### Public API

The library's components suitable for consumption comprise

* service:
    * service factory
    * return label service
    * data transfer object builder
* data transfer objects:
    * authentication storage
    * booking confirmation with label data

#### Usage

```php
$authStorage = new \Dhl\Sdk\ParcelDe\Returns\Auth\AuthenticationStorage(
    'apiKey',
    'user',
    'password'
);
$logger = new \Psr\Log\NullLogger();

$serviceFactory = new \Dhl\Sdk\ParcelDe\Returns\Service\ServiceFactory();
$service = $serviceFactory->createReturnLabelService($authStorage, $logger, $sandbox = true);

$requestBuilder = new \Dhl\Sdk\ParcelDe\Returns\Model\ReturnLabelRequestBuilder();
$requestBuilder->setReceiverId($returnRecipient = 'deu');
$requestBuilder->setShipper(
    $name = 'Jane Doe',
    $countryCode = 'DEU',
    $postalCode = '53113',
    $city = 'Bonn',
    $streetName = 'Sträßchensweg',
    $streetNumber = '2'
);

$returnOrder = $requestBuilder->create();
$confirmation = $service->createReturnOrder($returnOrder);
```
