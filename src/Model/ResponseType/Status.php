<?php

/**
 * See LICENSE.md for license details.
 */

declare(strict_types=1);

namespace Dhl\Sdk\ParcelDe\Returns\Model\ResponseType;

class Status
{
    private string $title;

    private int $status;

    /**
     * A URI reference [RFC3986] that identifies the problem type and is human-readable.
     */
    private ?string $type;

    /**
     * A human-readable explanation specific to this occurrence of the problem.
     */
    private ?string $detail;

    /**
     * A URI reference that identifies the specific occurrence of the problem.
     */
    private ?string $instance;

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function getDetail(): ?string
    {
        return $this->detail;
    }

    public function getInstance(): ?string
    {
        return $this->instance;
    }
}
