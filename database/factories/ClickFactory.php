<?php

namespace Database\Factories;

use App\Models\Click;
use App\Models\Short;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Click>
 */
class ClickFactory extends Factory
{
    protected $model = Click::class;

    public function definition(): array
    {
        return [
            'short_id' => Short::factory(),
            'ip_address' => hash('sha256', $this->faker->ipv4()),
            'user_agent' => $this->faker->userAgent(),
            'referrer' => $this->faker->optional(0.7)->url(),
            'clicked_at' => $this->faker->dateTimeBetween('-30 days', 'now'),
        ];
    }
}
