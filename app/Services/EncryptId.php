<?php

namespace App\Services;

use Illuminate\Support\Facades\Crypt;

class EncryptId
{
    public function encrypt(int $id): string
    {
        return Crypt::encryptString((string) $id);
    }

    public function decrypt(string $encrypted): int
    {
        return (int) Crypt::decryptString($encrypted);
    }
}
