<?php

namespace Database\Seeders\Complaint;

use App\Enums\ComplaintStatus;
use App\Enums\ComplaintType;
use App\Models\Admin;
use App\Models\Complaint;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ComplaintSeeder extends Seeder
{
    /** @var int Max rows per INSERT */
    private const CHUNK_SIZE = 500;

    private const COMPLAINT_COUNT = 10000;

    private const IMAGES_PER_COMPLAINT = 3;

    private const REPLAYS_PER_COMPLAINT = 2;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $complaintCount = self::COMPLAINT_COUNT;
        $chunk = self::CHUNK_SIZE;
        $now = now();

        $userIds = DB::table('users')->orderBy('id')->pluck('id')->all();
        if ($userIds === []) {
            return;
        }

        $adminId = (int) DB::table('admins')->orderBy('id')->value('id');
        if ($adminId === 0) {
            return;
        }

        $types = array_map(static fn (ComplaintType $c) => $c->value, ComplaintType::cases());
        $statuses = array_map(static fn (ComplaintStatus $s) => $s->value, ComplaintStatus::cases());

        $beforeId = (int) DB::table('complaints')->max('id');

        $complaintRows = [];
        for ($i = 0; $i < $complaintCount; $i++) {
            $complaintRows[] = [
                'name' => fake()->name(),
                'phone' => fake()->regexify('\+9665[0-9]{8}'),
                // Never use fake()->unique()->safeEmail() for large batches — Faker's unique pool exhausts.
                'email' => sprintf('complaint.seed.%d.%s@example.test', $i, Str::lower(Str::random(10))),
                'subject' => fake()->sentence(),
                'complaint' => fake()->paragraph(),
                'type' => fake()->randomElement($types),
                'status' => fake()->randomElement($statuses),
                'complaiantable_type' => User::class,
                'complaiantable_id' => fake()->randomElement($userIds),
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        foreach (array_chunk($complaintRows, $chunk) as $batch) {
            DB::table('complaints')->insert($batch);
        }

        $complaintIds = DB::table('complaints')
            ->where('id', '>', $beforeId)
            ->orderBy('id')
            ->pluck('id')
            ->all();

        $imageRows = [];
        foreach ($complaintIds as $complaintId) {
            for ($j = 0; $j < self::IMAGES_PER_COMPLAINT; $j++) {
                $imageRows[] = [
                    'complaint_id' => $complaintId,
                    'file_type' => fake()->fileExtension(),
                    'file_name' => fake()->word().'.'.fake()->fileExtension(),
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
        }
        foreach (array_chunk($imageRows, $chunk) as $batch) {
            DB::table('complaint_images')->insert($batch);
        }

        $replayRows = [];
        foreach ($complaintIds as $complaintId) {
            for ($j = 0; $j < self::REPLAYS_PER_COMPLAINT; $j++) {
                $replayRows[] = [
                    'replay' => fake()->sentence(),
                    'replayable_id' => $complaintId,
                    'replayable_type' => Complaint::class,
                    'replaybyable_id' => $adminId,
                    'replaybyable_type' => Admin::class,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
        }
        foreach (array_chunk($replayRows, $chunk) as $batch) {
            DB::table('replays')->insert($batch);
        }
    }
}
