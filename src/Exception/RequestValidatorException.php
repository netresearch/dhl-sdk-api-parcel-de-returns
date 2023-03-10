<?php

/**
 * See LICENSE.md for license details.
 */

declare(strict_types=1);

namespace Dhl\Sdk\ParcelDe\Returns\Exception;

/**
 * Class RequestValidatorException
 *
 * A special instance of the DetailedServiceException which is
 * caused by invalid request data before a web service request was sent.
 *
 * @todo(nr): extend DetailedServiceException once it exists
 * @api
 */
class RequestValidatorException extends ServiceException
{
}
