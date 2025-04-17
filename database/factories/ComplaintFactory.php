<?php

namespace Database\Factories;

use App\Enums\ComplaintStatus;
use App\Enums\ComplaintType;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Complaint>
 */
class ComplaintFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name'                  => $this->faker->name(),
            'phone'                 => $this->faker->regexify('\+9665[0-9]{8}'),
            'email'                 => $this->faker->unique()->safeEmail(),
            'subject'               => $this->faker->sentence(),
            'complaint'             => $this->faker->paragraph(),
            'type'                  => $this->faker->randomElement(ComplaintType::cases()),
            'status'                => $this->faker->randomElement(ComplaintStatus::cases()),
            'complaiantable_type'   => 'App\Models\User',
            'complaiantable_id'     => rand(1 , User::count()),
        ];
    }
}
