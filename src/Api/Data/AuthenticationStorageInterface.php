<?php

/**
 * See LICENSE.md for license details.
 */

declare(strict_types=1);

namespace Dhl\Sdk\ParcelDe\Returns\Api\Data;

/**
 * Interface AuthenticationStorageInterface
 *
 * @api
 */
interface AuthenticationStorageInterface
{
    public function getUser(): string;

    public function getPassword(): string;

    public function getApiKey(): string;
}
