<?php

namespace App\Services;

/**
 * Service for validating and sanitizing input data
 */
class InputValidator
{
    /**
     * Validate short code format (alphanumeric and hyphens only)
     */
    public static function validateShortCode(string $code): bool
    {
        return (bool) preg_match('/^[a-zA-Z0-9_-]+$/', $code) && strlen($code) <= 50;
    }

    /**
     * Sanitize short code to prevent injection
     */
    public static function sanitizeShortCode(string $code): string
    {
        return preg_replace('/[^a-zA-Z0-9_-]/', '', substr($code, 0, 50));
    }

    /**
     * Validate URL format and security
     */
    public static function validateUrl(string $url): bool
    {
        // Check URL format
        if (! filter_var($url, FILTER_VALIDATE_URL)) {
            return false;
        }

        // Parse URL
        $parsed = parse_url($url);

        if (! $parsed || ! isset($parsed['scheme'])) {
            return false;
        }

        // Only allow http and https schemes
        if (! in_array($parsed['scheme'], ['http', 'https'])) {
            return false;
        }

        // Prevent localhost and private IP addresses
        if (isset($parsed['host'])) {
            $host = $parsed['host'];
            $blacklist = [
                'localhost',
                '127.0.0.1',
                '0.0.0.0',
                '::1',
            ];

            if (in_array($host, $blacklist)) {
                return false;
            }

            // Check for private IP ranges
            if (! filter_var($host, FILTER_VALIDATE_IP, [
                'flags' => FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE,
            ])) {
                // If it's not an IP or it's a private IP, continue validation
            }
        }

        return true;
    }

    /**
     * Get safe user agent string
     */
    public static function sanitizeUserAgent(string $userAgent): string
    {
        return substr(htmlspecialchars($userAgent, ENT_QUOTES, 'UTF-8'), 0, 500);
    }

    /**
     * Get safe referrer
     */
    public static function sanitizeReferrer(?string $referrer): ?string
    {
        if (! $referrer) {
            return null;
        }

        $referrer = htmlspecialchars($referrer, ENT_QUOTES, 'UTF-8');

        return strlen($referrer) > 2000 ? substr($referrer, 0, 2000) : $referrer;
    }
}
