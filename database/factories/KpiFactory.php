<?php

namespace Database\Factories;

use App\Models\Kpi;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Enum\KpiStatus;

/**
 * @extends Factory<Kpi>
 */
class KpiFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
        'description' => fake()->sentence(8),
        'assigned_to' => User::factory(),
        'assigned_by' => User::factory(),
            'assigned_date' => fake()->dateTimeBetween('-2 months', 'now'),
            'status' => KpiStatus::Assigned,
            'notes' => null,
        ];
    }
}
