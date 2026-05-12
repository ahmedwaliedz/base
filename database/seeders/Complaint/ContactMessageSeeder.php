<?php

namespace Database\Seeders\Complaint;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ContactMessageSeeder extends Seeder
{
    /** @var int Max rows per INSERT */
    private const CHUNK_SIZE = 500;

    private const COUNT = 10000;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $count = self::COUNT;
        $chunk = self::CHUNK_SIZE;
        $now = now();

        $userIds = DB::table('users')->orderBy('id')->pluck('id')->all();
        if ($userIds === []) {
            return;
        }

        $rows = [];
        for ($i = 0; $i < $count; $i++) {
            $rows[] = [
                'name' => fake()->name(),
                // Never use fake()->unique()->safeEmail() for large batches — Faker's unique pool exhausts.
                'email' => sprintf('contact.seed.%d.%s@example.test', $i, Str::lower(Str::random(10))),
                'phone' => fake()->regexify('\+9665[0-9]{8}'),
                'subject' => fake()->sentence(),
                'message' => fake()->paragraph(),
                'contactable_type' => User::class,
                'contactable_id' => fake()->randomElement($userIds),
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        foreach (array_chunk($rows, $chunk) as $batch) {
            DB::table('contact_messages')->insert($batch);
        }
    }
}
