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
     * Validate URL format and security (SSRF prevention)
     */
    public static function validateUrl(string $url): bool
    {
        if (! filter_var($url, FILTER_VALIDATE_URL)) {
            return false;
        }

        $parsed = parse_url($url);

        if (! $parsed || ! isset($parsed['scheme'], $parsed['host'])) {
            return false;
        }

        if (! in_array($parsed['scheme'], ['http', 'https'], true)) {
            return false;
        }

        $host = $parsed['host'];

        $blockedHosts = [
            'localhost',
            '127.0.0.1',
            '0.0.0.0',
            '::1',
            '169.254.169.254',
            'metadata.google.internal',
        ];

        if (in_array(strtolower($host), $blockedHosts, true)) {
            return false;
        }

        if (filter_var($host, FILTER_VALIDATE_IP)) {
            if (! filter_var($host, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
                return false;
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
