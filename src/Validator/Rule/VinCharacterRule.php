<?php

declare(strict_types=1);

namespace App\Validator\Rule;

class VinCharacterRule implements VinRuleInterface
{
    private string $errorMessage = '';

    public function validate(string $vin): bool
    {
        // VIN can only contain uppercase alphanumeric characters
        // and must exclude I, O, Q (ISO 3779 standard)
        if (!preg_match('/^[A-HJ-NPR-Z0-9]{17}$/', $vin)) {
            $this->errorMessage = 'VIN contains invalid characters. Allowed: A-Z (except I, O, Q) and 0-9';
            return false;
        }

        return true;
    }

    public function getErrorMessage(): string
    {
        return $this->errorMessage;
    }
}
