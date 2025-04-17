<?php

namespace Database\Seeders\Complaint;

use App\Models\Complaint;
use App\Models\ComplaintImage;
use App\Models\Replay;
use Illuminate\Database\Seeder;

class ComplaintSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Complaint::factory()->count(100)->create()->each(function ($complaint) {
            ComplaintImage::factory()->count(3)->create([
                'complaint_id' => $complaint->id,
            ]);
            Replay::factory()->count(3)->create([
                'replayable_id' => $complaint->id,
            ]);
        });
    }
}
