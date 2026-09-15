<?php

declare(strict_types=1);

namespace App\Validator\Rule;

class VinChecksumRule implements VinRuleInterface
{
    private string $errorMessage = '';

    /**
     * Validate VIN checksum using ISO 3779 standard
     * Position 9 (index 8) is the check digit
     */
    public function validate(string $vin): bool
    {
        $transliteration = [
            'A' => 1, 'B' => 2, 'C' => 3, 'D' => 4, 'E' => 5, 'F' => 6,
            'G' => 7, 'H' => 8, 'J' => 1, 'K' => 2, 'L' => 3, 'M' => 4,
            'N' => 5, 'P' => 7, 'R' => 9, 'S' => 2, 'T' => 3, 'U' => 4,
            'V' => 5, 'W' => 6, 'X' => 7, 'Y' => 8, 'Z' => 9
        ];

        $weights = [8, 7, 6, 5, 4, 3, 2, 10, 0, 9, 8, 7, 6, 5, 4, 3, 2];

        $sum = 0;

        for ($i = 0; $i < 17; $i++) {
            $char = $vin[$i];
            $value = ctype_digit($char) ? (int)$char : ($transliteration[$char] ?? 0);
            $sum += $value * $weights[$i];
        }

        $checkDigit = $sum % 11;
        $checkDigit = $checkDigit === 10 ? 'X' : (string)$checkDigit;
        $actualCheckDigit = $vin[8];

        if ($checkDigit !== $actualCheckDigit) {
            $this->errorMessage = sprintf(
                'Invalid checksum: expected %s at position 9, got %s',
                $checkDigit,
                $actualCheckDigit
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
