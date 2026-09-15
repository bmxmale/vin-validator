<?php

require_once __DIR__ . '/../vendor/autoload.php';

use App\Validator\VinValidator;

// Create validator instance
$validator = new VinValidator();

// Test with valid VIN
$vin = '1HGBH41JXMN109186';

if ($validator->validate($vin)) {
    echo "✓ Valid VIN: $vin\n";
} else {
    echo "✗ Invalid VIN: $vin\n";
    foreach ($validator->getErrors() as $error) {
        echo "  - $error\n";
    }
}

echo "\n";

// Test with invalid VIN
$invalidVin = '1HGBH41JXMN10918';

if ($validator->validate($invalidVin)) {
    echo "✓ Valid VIN: $invalidVin\n";
} else {
    echo "✗ Invalid VIN: $invalidVin\n";
    foreach ($validator->getErrors() as $error) {
        echo "  - $error\n";
    }
}
