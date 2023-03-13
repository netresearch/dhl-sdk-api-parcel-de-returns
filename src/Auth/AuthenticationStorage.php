<?php

/**
 * See LICENSE.md for license details.
 */

declare(strict_types=1);

namespace Dhl\Sdk\ParcelDe\Returns\Auth;

use Dhl\Sdk\ParcelDe\Returns\Api\Data\AuthenticationStorageInterface;

class AuthenticationStorage implements AuthenticationStorageInterface
{
    public function __construct(
        private readonly string $apiKey,
        private readonly string $user,
        private readonly string $password,
    ) {
    }

    public function getApiKey(): string
    {
        return $this->apiKey;
    }

    public function getUser(): string
    {
        return $this->user;
    }

    public function getPassword(): string
    {
        return $this->password;
    }
}
