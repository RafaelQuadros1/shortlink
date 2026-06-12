<?php

namespace App\Services;

class Encode
{
    private string $alphabet;

    private int $base;

    public function __construct()
    {
        $this->alphabet = (new Alphabet)->getEncrypted();
        $this->base = strlen($this->alphabet);
    }

    public function code(int $number): string
    {
        if ($number < 0) {
            throw new \InvalidArgumentException('Number must be non-negative.');
        }

        $code = '';

        if ($number == 0) {
            return $this->alphabet[0];
        }

        while ($number > 0) {
            $code = $this->alphabet[$number % $this->base].$code;
            $number = intdiv($number, $this->base);
        }

        return $code;
    }
}
