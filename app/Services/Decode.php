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
            $position = strpos($this->alphabet, $code[$i]);

            if ($position === false) {
                throw new \InvalidArgumentException("Invalid character in code: {$code[$i]}");
            }

            $result = $result * $this->base + $position;
        }

        return $result;
    }
}
