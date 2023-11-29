<?php

/**
 * See LICENSE.md for license details.
 */

declare(strict_types=1);

namespace Dhl\Sdk\ParcelDe\Returns\Model\ResponseType;

class Label
{
    /**
     * The encoded byte stream.
     */
    private string $b64;

    public function getB64(): string
    {
        return $this->b64;
    }
}
