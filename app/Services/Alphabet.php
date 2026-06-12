<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;

class Alphabet
{
    private const ORIGINAL = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

    private const CACHE_KEY = 'alphabet:encrypted';

    public function getEncrypted(): string
    {
        return Cache::remember(self::CACHE_KEY, now()->addYear(), fn () => $this->shuffle(self::ORIGINAL));
    }

    public function getOriginal(): string
    {
        return self::ORIGINAL;
    }

    private function shuffle(string $alphabet): string
    {
        $secret = base64_decode(substr(config('app.key'), 7));
        $chars = str_split($alphabet);
        $seed = 0;

        for ($i = 0; $i < strlen($secret); $i++) {
            $seed = ($seed * 31 + ord($secret[$i])) & 0x7FFFFFFF;
        }

        for ($i = count($chars) - 1; $i > 0; $i--) {
            $seed = ($seed * 1103515245 + 12345) & 0x7FFFFFFF;
            $j = $seed % ($i + 1);
            [$chars[$i], $chars[$j]] = [$chars[$j], $chars[$i]];
        }

        return implode('', $chars);
    }
}
