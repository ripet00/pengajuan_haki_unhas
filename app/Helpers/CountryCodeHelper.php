<?php

if (!function_exists('getCountryCodes')) {
    /**
     * Get list of country codes
     */
    function getCountryCodes()
    {
        return [
            '+62' => '🇮🇩 Indonesia (+62)',
            '+1' => '🇺🇸 United States (+1)',
            '+44' => '🇬🇧 United Kingdom (+44)',
            '+65' => '🇸🇬 Singapore (+65)',
            '+60' => '🇲🇾 Malaysia (+60)',
            '+66' => '🇹🇭 Thailand (+66)',
            '+84' => '🇻🇳 Vietnam (+84)',
            '+63' => '🇵🇭 Philippines (+63)',
            '+86' => '🇨🇳 China (+86)',
            '+81' => '🇯🇵 Japan (+81)',
            '+82' => '🇰🇷 South Korea (+82)',
            '+91' => '🇮🇳 India (+91)',
            '+971' => '🇦🇪 UAE (+971)',
            '+966' => '🇸🇦 Saudi Arabia (+966)',
            '+33' => '🇫🇷 France (+33)',
            '+49' => '🇩🇪 Germany (+49)',
            '+39' => '🇮🇹 Italy (+39)',
            '+34' => '🇪🇸 Spain (+34)',
            '+31' => '🇳🇱 Netherlands (+31)',
            '+41' => '🇨🇭 Switzerland (+41)',
            '+46' => '🇸🇪 Sweden (+46)',
            '+47' => '🇳🇴 Norway (+47)',
            '+45' => '🇩🇰 Denmark (+45)',
            '+61' => '🇦🇺 Australia (+61)',
            '+64' => '🇳🇿 New Zealand (+64)',
            '+27' => '🇿🇦 South Africa (+27)',
            '+55' => '🇧🇷 Brazil (+55)',
            '+52' => '🇲🇽 Mexico (+52)',
            '+54' => '🇦🇷 Argentina (+54)',
            '+7' => '🇷🇺 Russia (+7)',
            '+90' => '🇹🇷 Turkey (+90)',
            '+20' => '🇪🇬 Egypt (+20)',
            '+234' => '🇳🇬 Nigeria (+234)',
            '+254' => '🇰🇪 Kenya (+254)',
        ];
    }
}

if (!function_exists('formatWhatsAppNumber')) {
    /**
     * Format phone number for WhatsApp URL
     * @param string $phoneNumber - Phone number (08xxxx format)
     * @param string $countryCode - Country code (+62)
     * @return string - Formatted number for WhatsApp
     */
    function formatWhatsAppNumber($phoneNumber, $countryCode = '+62')
    {
        // Remove all non-numeric characters
        $cleanPhone = preg_replace('/[^0-9]/', '', $phoneNumber);
        
        // Remove leading zero if exists
        if (substr($cleanPhone, 0, 1) === '0') {
            $cleanPhone = substr($cleanPhone, 1);
        }
        
        // Remove '+' from country code for WhatsApp format
        $cleanCountryCode = str_replace('+', '', $countryCode);
        
        return $cleanCountryCode . $cleanPhone;
    }
}

if (!function_exists('generateWhatsAppUrl')) {
    /**
     * Generate WhatsApp URL with pre-filled message
     * @param string $phoneNumber - Phone number (08xxxx format)
     * @param string $countryCode - Country code (+62)
     * @param string $message - Pre-filled message
     * @return string - WhatsApp URL
     */
    function generateWhatsAppUrl($phoneNumber, $countryCode = '+62', $message = '')
    {
        $formattedNumber = formatWhatsAppNumber($phoneNumber, $countryCode);
        $encodedMessage = urlencode($message);
        
        return "https://wa.me/{$formattedNumber}?text={$encodedMessage}";
    }
}