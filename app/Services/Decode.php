<?php

namespace App\Services;

class Decode
{
    private string $alphabet;

    private int $base;

    public function __construct()
    {
        $this->alphabet = (new Alphabet)->getEncrypted();
        $this->base = strlen($this->alphabet);
    }

    public function decode(string $code): int
    {
        $result = 0;
        $length = strlen($code);

        for ($i = 0; $i < $length; $i++) {
            $result = $result * $this->base + strpos($this->alphabet, $code[$i]);
        }

        return $result;
    }
}
