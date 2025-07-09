<?php

namespace Database\Factories;

use App\Enums\AdminType;
use App\Models\Admin;
use App\Models\Country;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\Sequence;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Admin>
 */
class AdminFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name'               => $this->faker->name,
            'phone'              => $this->faker->unique()->regexify('\+05[0-9]{8}'),
            'country_code'       => $this->faker->randomElement(Country::get()->pluck('code')->toArray()),
            'is_notify'          => $this->faker->boolean,
            'created_at'         => now()->subDays(rand(1, 30)),

        ];
    }
    public function withSequencedAttributes(): self
    {
        return $this->state(new Sequence(
            function (Sequence $sequence) {
                $isFirst = $sequence->index === 0;
                return [
                    'type'          => $isFirst ? AdminType::SUPER_ADMIN  : AdminType::ADMIN,
                    'is_blocked'    => $isFirst ? 0 : $this->faker->boolean(),
                    'role_id'       => $isFirst ? null : Role::inRandomOrder()->value('id'),
                    'email'         => $isFirst ? env('DASHBOARDEMAIL', 'aait@info.com') : $this->faker->unique()->safeEmail(),
                    'password'      => $isFirst ? 'Admin@123' : 'Password@123',
                ];
            }
        ));
    }
}
