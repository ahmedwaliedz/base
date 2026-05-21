<?php

namespace Database\Seeders\MorePage;

use App\Enums\PageType;
use App\Models\Page;
use Illuminate\Database\Seeder;

class PageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pages = [
            [
                "slug"       => "terms",
                "icon"       => "terms.png",
                "type"       => PageType::USER,
                "en"         => [
                    "title"   => "Terms and Conditions",
                    "content" => "These are the terms and conditions of SGA Software Company. Please read them carefully before using our services."
                ],
                "ar"         => [
                    "title"   => "شروط الاستخدام",
                    "content" => "هذه هي شروط الاستخدام الخاصة بSGA. يرجى قراءتها بعناية قبل استخدام خدماتنا."
                ],
            ],
            [
                "slug"       => "privacy",
                "icon"       => "privacy.png",
                "type"       => PageType::USER,
                "en"         => [
                    "title"   => "Privacy Policy",
                    "content" => "This privacy policy describes how SGA Software Company collects, uses, and protects your personal information."
                ],
                "ar"         => [
                    "title"   => "سياسة الخصوصية",
                    "content" => "توضح سياسة الخصوصية هذه كيفية جمع واستخدام وحماية المعلومات الشخصية لدى SGA."
                ],
            ],
            [
                "slug"       => "about",
                "icon"       => "about.png",
                "type"       => PageType::USER,
                "en"         => [
                    "title"   => "About Us",
                    "content" => "SGA is a leading software company specialized in innovative solutions. We pride ourselves on quality and excellence."
                ],
                "ar"         => [
                    "title"   => "من نحن",
                    "content" => "SGA هي شركة رائدة في مجال البرمجيات المتطورة والحلول المبتكرة. نفخر بجودة خدماتنا وتميزنا."
                ],
            ],
            [
                "slug"       => "contact",
                "icon"       => "contact.png",
                "type"       => PageType::USER,
                "en"         => [
                    "title"   => "Contact Us",
                    "content" => "Feel free to contact SGA Software Company for any inquiries or support. You can reach us via email, phone, or our contact form."
                ],
                "ar"         => [
                    "title"   => "اتصل بنا",
                    "content" => "لا تتردد في التواصل مع SGA لأي استفسارات أو دعم. يمكنك التواصل معنا عبر البريد الإلكتروني أو الهاتف أو نموذج الاتصال."
                ],
            ],
        ];

        foreach ($pages as $pageData) {
            Page::create($pageData);
        }
    }
}
