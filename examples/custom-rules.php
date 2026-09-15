<?php

require_once __DIR__ . '/../vendor/autoload.php';

use App\Validator\VinValidator;
use App\Validator\Rule\VinRuleInterface;
use App\Validator\Rule\VinChecksumRule;

// Create a custom rule
class VinBrandRule implements VinRuleInterface
{
    private string $errorMessage = '';
    private array $allowedBrands = ['1HG', '2HG', '3G1', '5GR'];

    public function validate(string $vin): bool
    {
        $prefix = substr($vin, 0, 3);

        if (!in_array($prefix, $this->allowedBrands, true)) {
            $this->errorMessage = sprintf(
                'VIN brand prefix %s is not allowed. Allowed: %s',
                $prefix,
                implode(', ', $this->allowedBrands)
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

// Create validator and add custom rule
$validator = new VinValidator();
$validator->addRule(new VinBrandRule());

$vin = '1HGBH41JXMN109186';

if ($validator->validate($vin)) {
    echo "✓ Valid VIN (with brand validation): $vin\n";
} else {
    echo "✗ Invalid VIN: $vin\n";
    foreach ($validator->getErrors() as $error) {
        echo "  - $error\n";
    }
}

echo "\n";

// Create validator without checksum validation
$lightValidator = new VinValidator();
$lightValidator->removeRule(VinChecksumRule::class);

echo "Validators configured: " . count($lightValidator->getRules()) . "\n";
echo "Removed: VinChecksumRule\n";
