<?php

namespace Database\Factories;

use App\Models\Country;
use App\Support\PhoneNormalizer;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name'                  => fake()->name(),
            'phone'                 => PhoneNormalizer::normalize($this->faker->unique()->regexify('\05[0-9]{8}')),
            'country_code'          => Country::inRandomOrder()->first()->code,
            'email'                 => fake()->unique()->userName() . '@gmail.com',
            'email_verified_at'     => now(),
            'password'              => 'password',
            'remember_token'        => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
