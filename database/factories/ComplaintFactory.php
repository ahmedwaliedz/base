<?php

namespace Database\Factories;

use App\Enums\ComplaintStatus;
use App\Enums\ComplaintType;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ComplaintFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name'                  => $this->faker->name(),
            'phone'                 => $this->faker->regexify('\+9665[0-9]{8}'),
            'email'                 => $this->faker->unique()->safeEmail(),
            'subject'               => $this->faker->sentence(),
            'complaint'             => $this->faker->paragraph(),
            'type'                  => $this->faker->randomElement(ComplaintType::cases())->value,
            'status'                => $this->faker->randomElement(ComplaintStatus::cases())->value,
            'complainantable_type'  => 'App\Models\User',
            'complainantable_id'    => User::factory(),
        ];
    }
}
