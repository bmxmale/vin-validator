<?php

declare(strict_types=1);

namespace App\Validator\Rule;

class VinLengthRule implements VinRuleInterface
{
    private const VIN_LENGTH = 17;
    private string $errorMessage = '';

    public function validate(string $vin): bool
    {
        if (strlen($vin) !== self::VIN_LENGTH) {
            $this->errorMessage = sprintf(
                'VIN must be exactly %d characters long, got %d',
                self::VIN_LENGTH,
                strlen($vin)
            );
            return false;
        }

        return true;
    }

    public function getErrorMessage(): string
    {
        return $this->errorMessage;
    }
}
