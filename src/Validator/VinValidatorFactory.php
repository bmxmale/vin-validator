<?php

declare(strict_types=1);

namespace App\Validator;

use App\Validator\Rule\VinRuleInterface;

class VinValidatorFactory
{
    private static array $customRules = [];

    /**
     * Register a custom rule globally
     */
    public static function registerCustomRule(VinRuleInterface $rule): void
    {
        self::$customRules[$rule::class] = $rule;
    }

    /**
     * Create a validator with default rules
     */
    public static function create(): VinValidator
    {
        return new VinValidator();
    }

    /**
     * Create a validator with custom rules
     */
    public static function createWithCustomRules(): VinValidator
    {
        $validator = new VinValidator();

        foreach (self::$customRules as $rule) {
            $validator->addRule($rule);
        }

        return $validator;
    }

    /**
     * Validate a VIN and return result object
     */
    public static function validateVin(string $vin, VinValidator $validator = null): VinValidationResult
    {
        $validator ??= self::create();
        $isValid = $validator->validate($vin);

        return new VinValidationResult(
            $isValid,
            strtoupper(trim($vin)),
            $validator->getErrors()
        );
    }
}
