<?php

namespace Database\Factories;

use App\Models\ApiKey;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ApiKey>
 */
class ApiKeyFactory extends Factory
{
    protected $model = ApiKey::class;

    public function definition(): array
    {
        $key = ApiKey::generateKey();

        return [
            'user_id' => User::factory(),
            'name' => fake()->word(),
            'key' => $key['hashed'],
            'key_lookup' => $key['key_lookup'],
            'abilities' => null,
        ];
    }
}
