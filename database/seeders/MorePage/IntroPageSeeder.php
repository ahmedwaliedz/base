<?php

namespace Database\Seeders\MorePage;

use App\Models\IntroPage;
use Illuminate\Database\Seeder;

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
            IntroPage::create($pageData);
        }
    }
}
