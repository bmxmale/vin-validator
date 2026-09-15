# VIN Validator

A simple, extensible PHP 8 VIN (Vehicle Identification Number) validator with modular validation methods. Each validation rule is implemented as a separate class, making it easy to extend and customize.

## Features

- ✅ **Modular Design** - Each validation rule is a separate class implementing `VinRuleInterface`
- ✅ **Easy to Extend** - Add custom rules by implementing a simple interface
- ✅ **PHP 8 Native** - Uses typed properties, constructor promotion, and modern PHP features
- ✅ **ISO 3779 Standard** - Full compliance with VIN checksum validation
- ✅ **Symfony Ready** - Perfect for integration with Symfony 8 API endpoints
- ✅ **Result Objects** - Structured response objects for API responses
- ✅ **Zero Dependencies** - Pure PHP, no external libraries required

## Installation

### Via Composer

```bash
composer require bmxmale/vin-validator
```

### Manual

Clone the repository and include the autoloader:

```php
require_once 'vendor/autoload.php';
```

## Quick Start

### Basic Validation

```php
use App\Validator\VinValidator;

$validator = new VinValidator();
$vin = '1HGBH41JXMN109186';

if ($validator->validate($vin)) {
    echo "Valid VIN!";
} else {
    foreach ($validator->getErrors() as $error) {
        echo $error . PHP_EOL;
    }
}
```

### Using Factory

```php
use App\Validator\VinValidatorFactory;

$result = VinValidatorFactory::validateVin('1HGBH41JXMN109186');

if ($result->isValid()) {
    echo "Valid VIN!";
}

// Convert to JSON for API response
echo json_encode($result->toArray());
```

## Validation Rules

The validator includes four default validation rules:

### 1. VinLengthRule
Validates that the VIN is exactly 17 characters long.

```
Error: "VIN must be exactly 17 characters long, got X"
```

### 2. VinCharacterRule
Validates that the VIN contains only valid characters (A-Z except I, O, Q, and 0-9) according to ISO 3779 standard.

```
Error: "VIN contains invalid characters. Allowed: A-Z (except I, O, Q) and 0-9"
```

### 3. VinFormatRule
Validates that specific positions contain digits:
- Position 10 (index 9): Model year (must be digit)
- Position 12 (index 11): Start of serial number (must be digit)

```
Error: "Invalid format: Position 10 must be a digit (model year)"
```

### 4. VinChecksumRule
Validates the check digit (position 9) using ISO 3779 checksum algorithm.

```
Error: "Invalid checksum: expected X at position 9, got Y"
```

## Usage Examples

### Example 1: Basic Validation

```php
use App\Validator\VinValidator;

$validator = new VinValidator();

// Valid VIN
if ($validator->validate('1HGBH41JXMN109186')) {
    echo "✓ Valid VIN\n";
} else {
    echo "✗ Invalid VIN\n";
    foreach ($validator->getErrors() as $error) {
        echo "  - $error\n";
    }
}
```

### Example 2: Selective Rule Validation

Remove specific rules to perform lighter validation:

```php
use App\Validator\VinValidator;
use App\Validator\Rule\VinChecksumRule;

$validator = new VinValidator();
$validator->removeRule(VinChecksumRule::class);

// Now validates length, characters, and format only
$validator->validate('1HGBH41JXMN109186');
```

### Example 3: Custom Validation Rules

Implement `VinRuleInterface` to create custom rules:

```php
use App\Validator\VinValidator;
use App\Validator\Rule\VinRuleInterface;

class VinBrandRule implements VinRuleInterface
{
    private string $errorMessage = '';
    private array $allowedBrands = ['1HG', '2HG', '3G1', '5GR'];

    public function validate(string $vin): bool
    {
        $prefix = substr($vin, 0, 3);

        if (!in_array($prefix, $this->allowedBrands, true)) {
            $this->errorMessage = sprintf(
                'VIN brand prefix %s is not allowed',
                $prefix
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

// Use it with validator
$validator = new VinValidator();
$validator->addRule(new VinBrandRule());

$validator->validate('1HGBH41JXMN109186');
```

### Example 4: Factory with Result Object

Perfect for API responses:

```php
use App\Validator\VinValidatorFactory;

$result = VinValidatorFactory::validateVin('1HGBH41JXMN109186');

echo json_encode($result->toArray(), JSON_PRETTY_PRINT);

// Output:
// {
//     "valid": true,
//     "vin": "1HGBH41JXMN109186",
//     "errors": [],
//     "metadata": {}
// }
```

## API Reference

### VinValidator

Main validator class with modular rule system.

#### Methods

```php
// Constructor - registers default rules
__construct()

// Add a validation rule
addRule(VinRuleInterface $rule): self

// Remove a rule by class name
removeRule(string $ruleClassName): self

// Validate a VIN
validate(string $vin): bool

// Get validation errors
getErrors(): string[]

// Get all registered rules
getRules(): VinRuleInterface[]
```

### VinValidationResult

Structured result object for validation responses.

#### Methods

```php
// Constructor
__construct(bool $isValid, string $vin, array $errors = [], array $metadata = [])

// Check if valid
isValid(): bool

// Get the VIN
getVin(): string

// Get validation errors
getErrors(): string[]

// Add metadata to result
addMetadata(string $key, mixed $value): self

// Get metadata (all or specific key)
getMetadata(string $key = null): mixed

// Convert to array (for JSON serialization)
toArray(): array
```

### VinValidatorFactory

Static factory for convenient validator creation.

#### Methods

```php
// Create validator with default rules
static create(): VinValidator

// Create validator with custom rules
static createWithCustomRules(): VinValidator

// Register a custom rule globally
static registerCustomRule(VinRuleInterface $rule): void

// Validate VIN and return result object
static validateVin(string $vin, VinValidator $validator = null): VinValidationResult
```

### VinRuleInterface

Interface for custom validation rules.

#### Methods

```php
// Validate VIN against this rule
validate(string $vin): bool

// Get error message if validation fails
getErrorMessage(): string
```

## Symfony 8 Integration

### Create a Service

```php
// src/Service/VinValidatorService.php

namespace App\Service;

use App\Validator\VinValidatorFactory;
use App\Validator\VinValidationResult;

class VinValidatorService
{
    public function validate(string $vin): VinValidationResult
    {
        return VinValidatorFactory::validateVin($vin);
    }
}
```

### Create an API Endpoint

```php
// src/Controller/Api/VinController.php

namespace App\Controller\Api;

use App\Service\VinValidatorService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/vin', name: 'api_vin_')]
class VinController extends AbstractController
{
    public function __construct(
        private VinValidatorService $vinValidator
    ) {}

    #[Route('/validate', name: 'validate', methods: ['POST'])]
    public function validate(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $vin = $data['vin'] ?? null;

        if (!$vin) {
            return $this->json([
                'error' => 'VIN is required'
            ], 400);
        }

        $result = $this->vinValidator->validate($vin);

        return $this->json($result->toArray());
    }
}
```

## Project Structure

```
vin-validator/
├── src/
│   └── Validator/
│       ├── VinValidator.php
│       ├── VinValidatorFactory.php
│       ├── VinValidationResult.php
│       └── Rule/
│           ├── VinRuleInterface.php
│           ├── VinLengthRule.php
│           ├── VinCharacterRule.php
│           ├── VinFormatRule.php
│           └── VinChecksumRule.php
├── examples/
│   ├── basic.php
│   ├── factory.php
│   └── custom-rules.php
├── composer.json
└── README.md
```

## Running Examples

Navigate to the project directory and run examples:

```bash
php examples/basic.php
php examples/factory.php
php examples/custom-rules.php
```

## Testing

Run the basic example to test the validator:

```bash
php examples/basic.php
```

Expected output:
```
✓ Valid VIN: 1HGBH41JXMN109186

✗ Invalid VIN: 1HGBH41JXMN10918
  - VIN must be exactly 17 characters long, got 16
```

## VIN Format Reference

A Vehicle Identification Number (VIN) has this structure:

```
Position:  1  2  3  4  5  6  7  8  9 10 11 12 13 14 15 16 17
           |--World Mfg---|  |--Descriptor--|CHK|---Serial---|
           |  Manufacturer  |Vehicle Attributes|  | Number  |
```

- **Positions 1-3**: World Manufacturer Identifier (WMI)
- **Positions 4-8**: Vehicle Descriptor Section (VDS)
- **Position 9**: Check Digit (calculated via ISO 3779)
- **Position 10**: Model Year (must be digit)
- **Position 11**: Plant Code
- **Positions 12-17**: Serial Number (12 must be digit)

**Note**: Characters I, O, Q are excluded from all positions per ISO 3779 standard.

## License

MIT License - see LICENSE file for details

## Author

bmxmale

## Contributing

Contributions are welcome! Feel free to submit issues or pull requests.

## Changelog

### v1.0.0 (Initial Release)
- Initial implementation of VIN validator
- Four default validation rules
- Factory pattern for easy instantiation
- Result objects for API responses
- Full ISO 3779 compliance
