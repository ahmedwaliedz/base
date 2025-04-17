<?php

namespace Database\Seeders\MorePage;

use App\Models\Social;
use Illuminate\Database\Seeder;

class SocialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $socials = [
            [
                'image' => 'facebook.png',
                'link'  => 'https://facebook.com/example',
            ],[
                'image' => 'twitter.png',
                'link'  => 'https://twitter.com/example',
            ],[
                'image' => 'instagram.png',
                'link'  => 'https://instagram.com/example',
            ],[
                'image' => 'linkedin.png',
                'link'  => 'https://linkedin.com/example',
            ],[
                'image' => 'telegram.png',
                'link'  => 'https://telegram.com/example',
            ],[
                'image' => 'youtube.png',
                'link'  => 'https://youtube.com/example',
            ],
        ];

        foreach ($socials as $socialData) {
            Social::create($socialData);
        }
    }
}
