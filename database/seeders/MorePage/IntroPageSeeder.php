<?php

namespace Database\Seeders\MorePage;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class IntroPageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $introPages = [
            [
                "link"       => "https://example.com/welcome",
                "image"      => "1.png",
                "is_active"  => true,
                "en"         => [
                    "title"   => "Welcome to Our App",
                    "description" => "Discover the amazing features we offer."
                ],
                "ar"         => [
                    "title"   => "مرحبًا بكم في تطبيقنا",
                    "description" => "استكشف الميزات الرائعة التي نقدمها."
                ],
            ],
            [
                "link"       => "https://example.com/features",
                "image"      => "2.png",
                "is_active"  => true,
                "en"         => [
                    "title"   => "Features",
                    "description" => "Our app provides innovative solutions to help you stay connected."
                ],
                "ar"         => [
                    "title"   => "الميزات",
                    "description" => "يقدم تطبيقنا حلولاً مبتكرة لمساعدتك على البقاء على اتصال."
                ],
            ],
            [
                "link"       => "https://example.com/get-started",
                "image"      => "get-3.png",
                "is_active"  => true,
                "en"         => [
                    "title"   => "Get Started",
                    "description" => "Sign up now and enjoy our app experience."
                ],
                "ar"         => [
                    "title"   => "ابدأ الآن",
                    "description" => "سجل الآن واستمتع بتجربة تطبيقنا."
                ],
            ],
        ];
        foreach ($introPages as $pageData) {
            $id = DB::table('intro_pages')->insertGetId([
                'image' => $pageData['image'],
                'link' => $pageData['link'],
                'is_active' => $pageData['is_active'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $translations = [];
            foreach (['en', 'ar'] as $locale) {
                $translations[] = [
                    'intro_page_id' => $id,
                    'locale' => $locale,
                    'title' => $pageData[$locale]['title'],
                    'description' => $pageData[$locale]['description'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            DB::table('intro_page_translations')->insert($translations);
        }
    }
}
