<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ComplaintImage>
 */
class ComplaintImageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'file_type' => $this->faker->fileExtension(),
            'file_name' => $this->faker->word() . '.' . $this->faker->fileExtension(),
        ];
    }
}
