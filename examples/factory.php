<?php

require_once __DIR__ . '/../vendor/autoload.php';

use App\Validator\VinValidatorFactory;

// Using factory for quick validation
$vin = '1HGBH41JXMN109186';
$result = VinValidatorFactory::validateVin($vin);

echo "VIN: " . $result->getVin() . "\n";
echo "Valid: " . ($result->isValid() ? 'Yes' : 'No') . "\n";

if (!$result->isValid()) {
    echo "Errors:\n";
    foreach ($result->getErrors() as $error) {
        echo "  - $error\n";
    }
}

// Convert to JSON for API response
echo "\nJSON Response:\n";
echo json_encode($result->toArray(), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
