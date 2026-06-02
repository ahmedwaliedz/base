<?php

namespace Database\Seeders\MorePage;

use App\Models\Seo;
use Illuminate\Database\Seeder;

class SeoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $records = [
            [
                'image' => null,
                'en' => [
                    'meta_title'       => 'Home | SGA',
                    'meta_description' => 'SGA Software Company — innovative software solutions for your business.',
                    'meta_keywords'    => 'SGA, software, solutions, technology, innovation',
                ],
                'ar' => [
                    'meta_title'       => 'الرئيسية | SGA',
                    'meta_description' => 'شركة SGA للبرمجيات — حلول برمجية مبتكرة لأعمالك.',
                    'meta_keywords'    => 'SGA, برمجيات, حلول, تقنية, ابتكار',
                ],
            ],
            [
                'image' => null,
                'en' => [
                    'meta_title'       => 'About Us | SGA',
                    'meta_description' => 'Learn more about SGA Software Company, our mission, vision, and values.',
                    'meta_keywords'    => 'SGA, about, company, mission, vision',
                ],
                'ar' => [
                    'meta_title'       => 'من نحن | SGA',
                    'meta_description' => 'تعرف على شركة SGA للبرمجيات، مهمتنا ورؤيتنا وقيمنا.',
                    'meta_keywords'    => 'SGA, من نحن, شركة, مهمة, رؤية',
                ],
            ],
            [
                'image' => null,
                'en' => [
                    'meta_title'       => 'Contact Us | SGA',
                    'meta_description' => 'Get in touch with SGA Software Company. Reach us via email, phone, or our contact form.',
                    'meta_keywords'    => 'SGA, contact, support, email, phone',
                ],
                'ar' => [
                    'meta_title'       => 'اتصل بنا | SGA',
                    'meta_description' => 'تواصل مع شركة SGA للبرمجيات. يمكنك الوصول إلينا عبر البريد الإلكتروني أو الهاتف أو نموذج الاتصال.',
                    'meta_keywords'    => 'SGA, اتصال, دعم, بريد إلكتروني, هاتف',
                ],
            ],
            [
                'image' => null,
                'en' => [
                    'meta_title'       => 'Terms and Conditions | SGA',
                    'meta_description' => 'Read the terms and conditions governing the use of SGA services.',
                    'meta_keywords'    => 'SGA, terms, conditions, legal',
                ],
                'ar' => [
                    'meta_title'       => 'شروط الاستخدام | SGA',
                    'meta_description' => 'اقرأ شروط وأحكام استخدام خدمات SGA.',
                    'meta_keywords'    => 'SGA, شروط, أحكام, قانوني',
                ],
            ],
        ];

        if (Seo::query()->exists()) {
            return;
        }

        foreach ($records as $data) {
            Seo::create($data);
        }
    }
}
