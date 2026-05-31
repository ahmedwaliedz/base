<?php

namespace Database\Seeders\MorePage;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PostSeeder extends Seeder
{
    public function run(): void
    {
        $posts = [
            [
                'is_active' => true,
                'image' => '1.png',
                'en' => [
                    'title' => 'Welcome to Our Platform',
                    'content' => 'We are excited to announce the launch of our new platform. This post covers everything you need to know about getting started and making the most of our services.',
                ],
                'ar' => [
                    'title' => 'مرحباً بكم في منصتنا',
                    'content' => 'يسعدنا الإعلان عن إطلاق منصتنا الجديدة. يغطي هذا المنشور كل ما تحتاج لمعرفته حول البدء والاستفادة القصوى من خدماتنا.',
                ],
            ],
            [
                'is_active' => true,
                'image' => '2.png',
                'en' => [
                    'title' => 'Top 10 Tips for Success',
                    'content' => 'Discover the top ten strategies that will help you achieve your goals. From planning to execution, these tips cover the essential steps for success in any endeavour.',
                ],
                'ar' => [
                    'title' => 'أفضل 10 نصائح للنجاح',
                    'content' => 'اكتشف أفضل عشر استراتيجيات ستساعدك على تحقيق أهدافك. من التخطيط إلى التنفيذ، تغطي هذه النصائح الخطوات الأساسية للنجاح في أي مسعى.',
                ],
            ],
            [
                'is_active' => false,
                'image' => '3.png',
                'en' => [
                    'title' => 'Platform Maintenance Notice',
                    'content' => 'Our platform will undergo scheduled maintenance on Saturday from 2:00 AM to 6:00 AM. Some features may be temporarily unavailable during this period.',
                ],
                'ar' => [
                    'title' => 'إشعار صيانة المنصة',
                    'content' => 'ستخضع منصتنا لصيانة مجدولة يوم السبت من الساعة 2:00 صباحاً حتى 6:00 صباحاً. قد تكون بعض الميزات غير متاحة مؤقتاً خلال هذه الفترة.',
                ],
            ],
            [
                'is_active' => true,
                'image' => '4.png',
                'en' => [
                    'title' => 'New Feature Release',
                    'content' => 'We are thrilled to introduce our latest feature that enhances user experience and provides powerful new tools to streamline your workflow.',
                ],
                'ar' => [
                    'title' => 'إصدار ميزة جديدة',
                    'content' => 'يسعدنا تقديم أحدث ميزاتنا التي تعزز تجربة المستخدم وتوفر أدوات قوية جديدة لتبسيط سير عملك.',
                ],
            ],
            [
                'is_active' => false,
                'image' => '5.png',
                'en' => [
                    'title' => 'End of Year Summary',
                    'content' => 'As the year comes to a close, we reflect on our achievements, growth, and the milestones we reached together with our valued community.',
                ],
                'ar' => [
                    'title' => 'ملخص نهاية العام',
                    'content' => 'مع اقتراب نهاية العام، نتأمل إنجازاتنا ونمونا والمعالم التي وصلنا إليها معاً بمجتمعنا القيم.',
                ],
            ],
        ];

        foreach ($posts as $data) {
            $id = DB::table('posts')->insertGetId([
                'is_active' => $data['is_active'],
                'image' => $data['image'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $translations = [];
            foreach (['en', 'ar'] as $locale) {
                $translations[] = [
                    'post_id' => $id,
                    'locale' => $locale,
                    'title' => $data[$locale]['title'],
                    'content' => $data[$locale]['content'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            DB::table('post_translations')->insert($translations);
        }
    }
}
