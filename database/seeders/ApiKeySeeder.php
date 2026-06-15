<?php

namespace Database\Seeders;

use App\Models\ApiKey;
use App\Models\User;
use Illuminate\Database\Seeder;

class ApiKeySeeder extends Seeder
{
    public function run(): void
    {
        $user = User::first() ?? User::factory()->create();

        $generated = ApiKey::generateKey();

        $apiKey = ApiKey::factory()->create([
            'user_id' => $user->id,
            'key' => $generated['hashed'],
        ]);

        $this->command->info("API Key created for {$user->email}: {$generated['plain']}");
    }
}
