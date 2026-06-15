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

        if (! self::isValidHost($host)) {
            return false;
        }

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

        if (isset($parsed['path']) && ! self::isValidPath($parsed['path'])) {
            return false;
        }

        return true;
    }

    /**
     * Validate host format: no underscores, valid TLD, no consecutive dots/hyphens
     */
    private static function isValidHost(string $host): bool
    {
        $host = strtolower($host);

        if (preg_match('/[_.]{2,}/', $host)) {
            return false;
        }

        if (str_starts_with($host, '-') || str_ends_with($host, '-') || str_starts_with($host, '.')) {
            return false;
        }

        if (preg_match('/[^a-z0-9\-\.]/', $host)) {
            return false;
        }

        if (! filter_var($host, FILTER_VALIDATE_IP)) {
            $parts = explode('.', $host);

            if (count($parts) < 2) {
                return false;
            }

            $tld = end($parts);

            if (strlen($tld) < 2 || preg_match('/^\d+$/', $tld)) {
                return false;
            }

            foreach ($parts as $part) {
                if ($part === '' || strlen($part) > 63) {
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * Validate URL path: no control characters
     */
    private static function isValidPath(string $path): bool
    {
        return ! preg_match('/[\x00-\x1f\x7f]/', $path);
    }

    /**
     * Get specific validation error message for a URL
     */
    public static function getUrlValidationError(string $url): string
    {
        if (! filter_var($url, FILTER_VALIDATE_URL)) {
            return 'O formato da URL é inválido.';
        }

        $parsed = parse_url($url);

        if (! $parsed || ! isset($parsed['scheme'], $parsed['host'])) {
            return 'A URL deve conter esquema (http/https) e domínio.';
        }

        if (! in_array($parsed['scheme'], ['http', 'https'], true)) {
            return 'Apenas URLs HTTP e HTTPS são permitidas.';
        }

        $host = $parsed['host'];

        if (! self::isValidHost($host)) {
            return 'O domínio da URL é inválido.';
        }

        $blockedHosts = [
            'localhost',
            '127.0.0.1',
            '0.0.0.0',
            '::1',
            '169.254.169.254',
            'metadata.google.internal',
        ];

        if (in_array(strtolower($host), $blockedHosts, true)) {
            return 'A URL contém um domínio não permitido.';
        }

        if (filter_var($host, FILTER_VALIDATE_IP)) {
            if (! filter_var($host, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
                return 'A URL não pode apontar para endereços de rede privados.';
            }
        }

        if (isset($parsed['path']) && ! self::isValidPath($parsed['path'])) {
            return 'A URL contém caracteres inválidos.';
        }

        return 'A URL fornecida é inválida.';
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
