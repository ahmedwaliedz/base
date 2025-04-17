<?php

namespace Database\Seeders\Complaint;

use App\Models\ContactMessage;
use App\Models\Replay;
use Illuminate\Database\Seeder;

class ContactMessageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ContactMessage::factory()->count(100)->create()->each(function ($contactMessage) {
            Replay::factory()->count(3)->create([
                'replayable_id' => $contactMessage->id,
            ]);
        });
    }
}
