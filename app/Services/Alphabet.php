<?php

namespace App\Services;

class Alphabet
{
    private const ORIGINAL = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

    private string $secret;

    private string $encrypted;

    public function __construct()
    {
        $this->secret = base64_decode(substr(config('app.key'), 7));
        $this->encrypted = $this->shuffle(self::ORIGINAL);
    }

    public function getEncrypted(): string
    {
        return $this->encrypted;
    }

    public function getOriginal(): string
    {
        return self::ORIGINAL;
    }

    private function shuffle(string $alphabet): string
    {
        $chars = str_split($alphabet);
        $seed = 0;

        for ($i = 0; $i < strlen($this->secret); $i++) {
            $seed = ($seed * 31 + ord($this->secret[$i])) & 0x7FFFFFFF;
        }

        for ($i = count($chars) - 1; $i > 0; $i--) {
            $seed = ($seed * 1103515245 + 12345) & 0x7FFFFFFF;
            $j = $seed % ($i + 1);
            [$chars[$i], $chars[$j]] = [$chars[$j], $chars[$i]];
        }

        return implode('', $chars);
    }
}
