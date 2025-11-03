<?php

declare(strict_types=1);

namespace App\Support;

class PhoneNormalizer
{
    /**
     * Normalize phone number to consistent format
     *
     * @param string $phone
     * @return string
     */
    public static function normalize(string $phone): string
    {
        // Remove all non-digit characters except +
        $phone = preg_replace('/[^\d+]/', '', $phone);
        
        // Remove leading zeros
        $phone = ltrim($phone, '0');
        
        // If phone doesn't start with +, add it for international format
        if (!str_starts_with($phone, '+')) {
            $phone = '+' . $phone;
        }
        
        return $phone;
    }

    /**
     * Check if phone number is in valid format
     *
     * @param string $phone
     * @return bool
     */
    public static function isValid(string $phone): bool
    {
        $normalized = self::normalize($phone);
        
        // Basic validation: starts with +, followed by country code and number
        return preg_match('/^\+[1-9]\d{6,14}$/', $normalized) === 1;
    }

    /**
     * Get country code from phone number
     *
     * @param string $phone
     * @return string|null
     */
    public static function getCountryCode(string $phone): ?string
    {
        $normalized = self::normalize($phone);
        
        if (!str_starts_with($normalized, '+')) {
            return null;
        }
        
        // Common country codes (simplified)
        $countryCodes = [
            '+1' => 'US/CA',
            '+44' => 'UK',
            '+33' => 'FR',
            '+49' => 'DE',
            '+39' => 'IT',
            '+34' => 'ES',
            '+966' => 'SA',
            '+971' => 'AE',
            '+965' => 'KW',
            '+974' => 'QA',
            '+973' => 'BH',
            '+968' => 'OM',
            '+962' => 'JO',
            '+961' => 'LB',
            '+963' => 'SY',
            '+964' => 'IQ',
            '+20' => 'EG',
            '+212' => 'MA',
            '+213' => 'DZ',
            '+216' => 'TN',
            '+218' => 'LY',
        ];
        
        foreach ($countryCodes as $code => $country) {
            if (str_starts_with($normalized, $code)) {
                return $code;
            }
        }
        
        return null;
    }
}
