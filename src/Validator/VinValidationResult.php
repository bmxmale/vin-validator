<?php

declare(strict_types=1);

namespace App\Validator;

class VinValidationResult
{
    public function __construct(
        private bool $isValid,
        private string $vin,
        private array $errors = [],
        private array $metadata = []
    ) {}

    public function isValid(): bool
    {
        return $this->isValid;
    }

    public function getVin(): string
    {
        return $this->vin;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    public function addMetadata(string $key, mixed $value): self
    {
        $this->metadata[$key] = $value;
        return $this;
    }

    public function getMetadata(string $key = null): mixed
    {
        if ($key === null) {
            return $this->metadata;
        }

        return $this->metadata[$key] ?? null;
    }

    /**
     * Convert result to array (useful for API responses)
     */
    public function toArray(): array
    {
        return [
            'valid' => $this->isValid,
            'vin' => $this->vin,
            'errors' => $this->errors,
            'metadata' => $this->metadata,
        ];
    }
}
