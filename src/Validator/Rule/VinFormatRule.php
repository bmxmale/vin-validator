<?php

declare(strict_types=1);

namespace App\Validator\Rule;

class VinFormatRule implements VinRuleInterface
{
    private string $errorMessage = '';

    public function validate(string $vin): bool
    {
        // Position 10 (index 9) must be a digit (model year)
        if (!ctype_digit($vin[9])) {
            $this->errorMessage = 'Invalid format: Position 10 must be a digit (model year)';
            return false;
        }

        // Position 12 (index 11) must be a digit (serial number start)
        if (!ctype_digit($vin[11])) {
            $this->errorMessage = 'Invalid format: Position 12 must be a digit';
            return false;
        }

        return true;
    }

    public function getErrorMessage(): string
    {
        return $this->errorMessage;
    }
}
