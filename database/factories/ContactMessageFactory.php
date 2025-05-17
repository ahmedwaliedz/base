<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ContactMessage>
 */
class ContactMessageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name'                  => $this->faker->name,
            'email'                 => $this->faker->unique()->safeEmail,
            'subject'               => $this->faker->sentence,
            'message'               => $this->faker->paragraph,
            'phone'                 => $this->faker->regexify('\+9665[0-9]{8}'),
            'contactable_type'      => 'App\Models\User',
            'contactable_id'        => rand(1 , User::count()),
        ];
    }
}
