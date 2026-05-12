<?php

namespace Database\Seeders\User;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SessionSeeder extends Seeder
{
    /** Max rows per INSERT (plan-03 — avoid oversized packets) */
    private const CHUNK_SIZE = 500;

    public function run(): void
    {
        $profile = config('seed.profile', 'light');
        $min = (int) config("seed.counts.{$profile}.sessions_per_user_min", 0);
        $max = (int) config("seed.counts.{$profile}.sessions_per_user_max", 0);

        if ($max <= 0) {
            return;
        }

        $userIds = DB::table('users')->orderBy('id')->pluck('id');
        $devices = [
            'Mozilla/5.0 (iPhone; CPU iPhone OS 17_2 like Mac OS X) AppleWebKit/605.1.15',
            'Mozilla/5.0 (Linux; Android 14; SM-S918B) AppleWebKit/537.36',
            'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 Chrome/121',
            'Mozilla/5.0 (Macintosh; Intel Mac OS X 14_2) AppleWebKit/605.1.15 Safari/17.2',
            'Mozilla/5.0 (iPad; CPU OS 17_1 like Mac OS X) AppleWebKit/605.1.15',
        ];

        $now = now();
        $rows = [];

        foreach ($userIds as $uid) {
            $perUser = random_int($min, $max);
            for ($i = 0; $i < $perUser; $i++) {
                $rows[] = [
                    'id' => Str::random(40),
                    'user_id' => $uid,
                    'ip_address' => long2ip(random_int(0, 4_294_967_295)),
                    'user_agent' => $devices[array_rand($devices)],
                    'payload' => base64_encode('s:0:"";'),
                    'last_activity' => $now->copy()->subSeconds(random_int(0, 60 * 60 * 24 * 30))->timestamp,
                ];
            }
            if (count($rows) >= self::CHUNK_SIZE) {
                DB::table('sessions')->insert($rows);
                $rows = [];
            }
        }

        if (count($rows) > 0) {
            DB::table('sessions')->insert($rows);
        }
    }
}
