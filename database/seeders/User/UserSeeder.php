<?php

namespace Database\Seeders\User;

use App\Models\Country;
use App\Support\PhoneNormalizer;
use App\Traits\HandleNumbersTrait;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    use HandleNumbersTrait;

    /** @var int Max rows per INSERT (avoid max_allowed_packet) */
    private const CHUNK_SIZE = 500;

    /** @var int Total users to create (raise locally for volume tests) */
    private const COUNT = 10000;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $count = self::COUNT;
        $chunk = self::CHUNK_SIZE;
        $now = now();

        $countryCode = Country::query()->value('code') ?? '966';

        $passwordHash = bcrypt('password');

        $rows = [];
        for ($i = 1; $i <= $count; $i++) {
            $phoneLocal = sprintf('05%08d', ($i - 1) % 100_000_000);
            $phone = PhoneNormalizer::normalize($phoneLocal);
            $phoneNormalized = ($this->fixPhone($countryCode) ?? '').($this->fixPhone($phone) ?? '');

            $rows[] = [
                'name' => fake()->name(),
                'image' => 'default.png',
                'email' => $i === 1 ? 'user@app.com' : "seed.user.{$i}.".Str::random(6).'@example.test',
                'phone' => $phone,
                'phone_normalized' => $phoneNormalized,
                'country_code' => $countryCode,
                'password' => $passwordHash,
                'email_verified_at' => $now,
                'phone_verified_at' => null,
                'last_activation_requested_at' => null,
                'is_blocked' => false,
                'is_notify' => true,
                'is_active' => true,
                'is_complete_info' => true,
                'remember_token' => Str::random(10),
                'created_at' => $now,
                'updated_at' => $now,
                'deleted_at' => null,
            ];
        }

        foreach (array_chunk($rows, $chunk) as $batch) {
            DB::table('users')->insert($batch);
        }
    }
}
