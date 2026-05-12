<?php

namespace Database\Seeders\User;

use App\Enums\OtpStatus;
use App\Enums\OtpType;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OtpSeeder extends Seeder
{
    /** Max rows per INSERT (plan-03 — avoid oversized packets) */
    private const CHUNK_SIZE = 500;

    public function run(): void
    {
        $profile = config('seed.profile', 'light');
        $min = (int) config("seed.counts.{$profile}.otps_per_user_min", 0);
        $max = (int) config("seed.counts.{$profile}.otps_per_user_max", 0);

        if ($max <= 0) {
            return;
        }

        $codeLength = (int) config('auth_codes.length', 6);
        $maxCode = (10 ** $codeLength) - 1;

        User::query()->orderBy('id')->chunkById(500, function ($users) use ($min, $max, $codeLength, $maxCode): void {
            $rows = [];
            $now = now();
            foreach ($users as $user) {
                $perUser = random_int($min, $max);
                for ($i = 0; $i < $perUser; $i++) {
                    $created = $now->copy()->subDays(random_int(1, 90));
                    $status = random_int(0, 1) === 1 ? OtpStatus::FINISHED : OtpStatus::ACTIVE;
                    $rows[] = [
                        'otpable_id' => $user->id,
                        'otpable_type' => User::class,
                        'changed_value' => null,
                        'country_code' => '966',
                        'verification_code' => str_pad((string) random_int(0, $maxCode), $codeLength, '0', STR_PAD_LEFT),
                        'verification_code_expire_at' => $created->copy()->addMinutes(10),
                        'type' => OtpType::ACTIVATE->value,
                        'status' => $status->value,
                        'tries' => random_int(0, 3),
                        'created_at' => $created,
                        'updated_at' => $now,
                    ];
                    if (count($rows) >= self::CHUNK_SIZE) {
                        DB::table('otps')->insert($rows);
                        $rows = [];
                    }
                }
            }
            if ($rows !== []) {
                DB::table('otps')->insert($rows);
            }
        });
    }
}
