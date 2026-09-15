<?php

declare(strict_types=1);

namespace App\Validator\Rule;

interface VinRuleInterface
{
    /**
     * Validate VIN against this rule
     */
    public function validate(string $vin): bool;

    /**
     * Get error message if validation fails
     */
    public function getErrorMessage(): string;
}
