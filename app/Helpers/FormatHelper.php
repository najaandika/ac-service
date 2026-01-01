<?php

namespace App\Helpers;

class FormatHelper
{
    /**
     * Format number to Indonesian Rupiah format.
     * 
     * @param int|float $amount
     * @param bool $withPrefix Include "Rp " prefix
     * @return string
     */
    public static function rupiah($amount, bool $withPrefix = true): string
    {
        $formatted = number_format($amount, 0, ',', '.');
        return $withPrefix ? 'Rp ' . $formatted : $formatted;
    }

    /**
     * Format phone number for WhatsApp link (international format).
     * 
     * @param string $phone
     * @return string Phone number in format 62xxx
     */
    public static function whatsappPhone(string $phone): string
    {
        // Remove non-numeric characters
        $phone = preg_replace('/[^0-9]/', '', $phone);
        
        // Convert 08xx to 628xx
        if (str_starts_with($phone, '0')) {
            $phone = '62' . substr($phone, 1);
        }
        
        // Add 62 if not present
        if (!str_starts_with($phone, '62')) {
            $phone = '62' . $phone;
        }
        
        return $phone;
    }

    /**
     * Generate WhatsApp URL.
     * 
     * @param string $phone
     * @param string|null $message Optional pre-filled message
     * @return string
     */
    public static function whatsappUrl(string $phone, ?string $message = null): string
    {
        $formattedPhone = self::whatsappPhone($phone);
        $url = "https://wa.me/{$formattedPhone}";
        
        if ($message) {
            $url .= '?text=' . urlencode($message);
        }
        
        return $url;
    }
}
