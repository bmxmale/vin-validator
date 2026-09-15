<?php

declare(strict_types=1);

namespace App\Validator\Rule;

class VinWmiRule implements VinRuleInterface
{
    private string $errorMessage = '';
    
    /**
     * Known World Manufacturer Identifiers
     * https://en.wikipedia.org/wiki/World_manufacturer_identifier
     */
    private array $validWmis = [
        // North America
        '1G1', '1G2', '1G3', '1G4', '1G5', '1G6', '1G7', '1G8', '1G9', // General Motors
        '1HG', '1H2', '1H3', '1H4', '1H5', '1H6', '1H7', '1H8', '1H9', // Honda
        '1FB', '1FC', '1FD', '1FE', '1FF', '1FG', '1FH', '1FT', // Ford
        '1J1', '1J2', '1J4', '1J5', '1J6', '1J7', '1J8', // Jeep/Chrysler
        '1LN', '1LS', // Lincoln
        '1M1', '1M2', '1M3', '1M4', '1M5', '1M6', '1M7', '1M8', '1M9', // Oldsmobile/GMC
        '1N1', '1N2', '1N3', '1N6', '1N7', '1N8', // Nissan
        '1P3', '1P4', '1P5', // Plymouth/Chrysler
        '1T1', '1T2', '1T3', '1T4', '1T5', '1T6', '1T7', '1T8', '1T9', // Toyota
        '1V1', '1V2', '1V3', '1V4', '1V5', '1V6', '1V7', '1V8', '1V9', // Volkswagen
        '1WA', '1WB', '1WC', '1WD', '1WE', '1WF', '1WG', '1WH', // Audi/VW
        '1YV', // Volvo
        '1Z3', '1Z5', '1Z6', '1Z7', '1Z8', // BMW
        '2F1', '2F2', '2F3', '2F4', '2F5', '2F6', '2F7', '2F8', '2F9', // Chrysler Canada
        '2G1', '2G2', '2G3', '2G4', '2G5', '2G6', '2G7', '2G8', '2G9', // General Motors Canada
        '2HG', '2H2', '2H3', '2H4', '2H5', '2H6', '2H7', '2H8', '2H9', // Honda Canada
        '2HM', '2HN', '2HP', '2HS', '2HT', '2HW', // Hyundai
        '2M1', '2M2', '2M3', '2M4', '2M5', '2M6', '2M7', '2M8', '2M9', // Oldsmobile/GMC Canada
        '2N1', '2N2', '2N3', '2N6', '2N7', '2N8', // Nissan Canada
        '2T1', '2T2', '2T3', '2T4', '2T5', '2T6', '2T7', '2T8', '2T9', // Toyota Canada
        '2VV', '2VW', '2VX', '2VY', '2VZ', // Volkswagen Mexico
        '2WM', // Hyundai USA
        '2WP', // Kia USA
        '3G1', '3G2', '3G3', '3G4', '3G5', '3G6', '3G7', '3G8', '3G9', // General Motors Mexico
        '3H3', '3H4', '3H5', '3H6', '3H7', '3H8', '3H9', // Honda Mexico
        '3N1', '3N2', '3N3', '3N6', '3N7', '3N8', // Nissan Mexico
        '3T1', '3T2', '3T3', '3T4', '3T5', '3T6', '3T7', '3T8', '3T9', // Toyota Mexico
        // Europe
        '4T1', '4T2', '4T3', '4T4', '4T5', '4T6', '4T7', '4T8', '4T9', // Toyota Europe
        '4U1', '4U2', '4U3', '4U4', '4U5', '4U6', '4U7', '4U8', '4U9', // Audi
        '5FB', '5FC', '5FD', '5FE', '5FF', '5FG', '5FH', '5FT', // Ford Europe
        '5GR', // Volvo
        '5N1', '5N2', '5N3', '5N6', '5N7', '5N8', // Nissan/Daimler
        '5NP', '5NPE', // Hyundai Europe
        '5NU', '5NUH', // Hyundai Europe
        '5T1', '5T2', '5T3', '5T4', '5T5', '5T6', '5T7', '5T8', '5T9', // Toyota Europe
        '6F1', '6F2', '6F3', '6F4', '6F5', '6F6', '6F7', '6F8', '6F9', // Daimler-Benz
        '6G1', '6G2', '6G3', '6G4', '6G5', '6G6', '6G7', '6G8', '6G9', // MAN/BMW/Rolls Royce
        '6M1', // Rover
        '6N1', // Nissan Europe
        '6T1', // Talbot
        '6V1', '6V2', '6V3', '6V4', '6V5', '6V6', '6V7', '6V8', '6V9', // Volkswagen Group
        '6W1', // Porsche
        '7A3', '7A4', '7A5', // Fiat/Lancia/Alfa Romeo
        '7B1', // Peugeot
        '7C1', '7C2', '7C3', // Citroen
        '7D1', // Dacia/Renault
        '7E1', '7E2', '7E3', '7E4', '7E5', '7E6', '7E7', '7E8', '7E9', // Fiat Group
        '7H1', '7H2', '7H3', // Isuzu
        '7M1', '7M2', '7M3', // Mitsubishi
        '7S3', '7S6', // Subaru
        '7T1', // Isuzu
        '8A1', '8A2', '8A3', // Daimler Group
        '8C1', // Isuzu/Isuzu UK
        '8D1', // Daimler
        '8E1', // Daimler
        '8F1', '8F2', '8F3', // Fiat
        '8G1', '8G2', '8G3', // General Motors Europe
        '8H1', // Honda Europe
        '8J1', '8J2', '8J3', // Jaguar
        '8N1', // Nissan Europe
        '8P1', // Porsche
        '8S1', // Subaru
        '8T1', // Toyota
        // Asia
        '9A1', '9A2', '9A3', // Suzuki
        '9B1', '9B2', '9B3', // Daimler/Hyundai
        '9C1', '9C2', '9C3', // Daewoo
        '9D1', // Daimler/Hyundai
        '9E1', '9E2', '9E3', // Hyundai
        '9F1', '9F2', '9F3', // Isuzu
        '9F8', // Isuzu Malaysia
        '9G1', '9G2', '9G3', // Isuzu Japan
        '9H1', '9H2', '9H3', // Honda Japan
        '9J1', '9J2', '9J3', // Jeep
        '9K1', '9K2', '9K3', // Daewoo
        '9L1', '9L2', '9L3', // Changan
        '9M1', '9M2', '9M3', // Mitsubishi
        '9M5', '9M6', '9M7', '9M8', '9M9', // Mitsubishi
        '9N1', // Nissan Japan
        '9P1', '9P2', '9P3', // Maruti
        '9R1', '9R2', '9R3', // Daimler
        '9S1', '9S2', '9S3', // Subaru
        '9S6', '9S7', '9S8', // Subaru
        '9T1', // Toyota Japan
        '9T4', '9T5', '9T6', '9T7', '9T8', '9T9', // Toyota Japan
        '9U1', '9U2', '9U3', // Daimler Malaysia
        '9U5', '9U6', '9U7', '9U8', '9U9', // Daimler
        '9V1', '9V2', '9V3', // Volkswagen
        '9VA', // Volkswagen
        '9W1', '9W2', '9W3', // Mercedes-Benz
        '9W6', '9W7', '9W8', '9W9', // Mercedes-Benz
        '9X1', '9X2', '9X3', // Zotye
        '9Y1', '9Y2', '9Y3', // Geely
        '9Z1', '9Z2', '9Z3', // Scania
    ];

    public function validate(string $vin): bool
    {
        $wmi = substr($vin, 0, 3);

        if (!in_array($wmi, $this->validWmis, true)) {
            $this->errorMessage = sprintf(
                'Invalid WMI (World Manufacturer Identifier): %s is not recognized',
                $wmi
            );
            return false;
        }

        return true;
    }

    public function getErrorMessage(): string
    {
        return $this->errorMessage;
    }

    /**
     * Get list of valid WMIs
     */
    public function getValidWmis(): array
    {
        return $this->validWmis;
    }

    /**
     * Check if a specific WMI is valid
     */
    public static function isValidWmi(string $wmi): bool
    {
        $rule = new self();
        return in_array($wmi, $rule->getValidWmis(), true);
    }
}
