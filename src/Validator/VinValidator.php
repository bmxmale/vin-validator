<?php

declare(strict_types=1);

namespace App\Validator;

use App\Validator\Rule\VinRuleInterface;
use App\Validator\Rule\VinLengthRule;
use App\Validator\Rule\VinCharacterRule;
use App\Validator\Rule\VinFormatRule;
use App\Validator\Rule\VinChecksumRule;

class VinValidator
{
    /**
     * @var VinRuleInterface[]
     */
    private array $rules = [];

    private array $errors = [];

    public function __construct()
    {
        $this->registerDefaultRules();
    }

    /**
     * Register default validation rules
     */
    private function registerDefaultRules(): void
    {
        $this->addRule(new VinLengthRule());
        $this->addRule(new VinCharacterRule());
        $this->addRule(new VinFormatRule());
        $this->addRule(new VinChecksumRule());
    }

    /**
     * Add a custom validation rule
     */
    public function addRule(VinRuleInterface $rule): self
    {
        $this->rules[] = $rule;
        return $this;
    }

    /**
     * Remove a rule by class name
     */
    public function removeRule(string $ruleClassName): self
    {
        $this->rules = array_filter(
            $this->rules,
            fn(VinRuleInterface $rule) => $rule::class !== $ruleClassName
        );
        return $this;
    }

    /**
     * Validate a VIN
     */
    public function validate(string $vin): bool
    {
        $this->errors = [];
        $vin = strtoupper(trim($vin));

        foreach ($this->rules as $rule) {
            if (!$rule->validate($vin)) {
                $this->errors[] = $rule->getErrorMessage();
            }
        }

        return empty($this->errors);
    }

    /**
     * Get validation errors
     *
     * @return string[]
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * Get all registered rules
     *
     * @return VinRuleInterface[]
     */
    public function getRules(): array
    {
        return $this->rules;
    }
}
