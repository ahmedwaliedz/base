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
                    "content" => "These are the terms and conditions of Awamer AlShabaka Software Company. Please read them carefully before using our services."
                ],
                "ar"         => [
                    "title"   => "شروط الاستخدام",
                    "content" => "هذه هي شروط الاستخدام الخاصة بشركة أوامر الشبكة للبرمجيات. يرجى قراءتها بعناية قبل استخدام خدماتنا."
                ],
            ],
            [
                "slug"       => "privacy",
                "icon"       => "privacy.png",
                "type"       => PageType::USER,
                "en"         => [
                    "title"   => "Privacy Policy",
                    "content" => "This privacy policy describes how Awamer AlShabaka Software Company collects, uses, and protects your personal information."
                ],
                "ar"         => [
                    "title"   => "سياسة الخصوصية",
                    "content" => "توضح سياسة الخصوصية هذه كيفية جمع واستخدام وحماية المعلومات الشخصية لدى شركة أوامر الشبكة للبرمجيات."
                ],
            ],
            [
                "slug"       => "about",
                "icon"       => "about.png",
                "type"       => PageType::USER,
                "en"         => [
                    "title"   => "About Us",
                    "content" => "Awamer AlShabaka is a leading software company specialized in innovative solutions. We pride ourselves on quality and excellence."
                ],
                "ar"         => [
                    "title"   => "من نحن",
                    "content" => "تعد شركة أوامر الشبكة من الشركات الرائدة في مجال البرمجيات المتطورة والحلول المبتكرة. نفخر بجودة خدماتنا وتميزنا."
                ],
            ],
            [
                "slug"       => "contact",
                "icon"       => "contact.png",
                "type"       => PageType::USER,
                "en"         => [
                    "title"   => "Contact Us",
                    "content" => "Feel free to contact Awamer AlShabaka Software Company for any inquiries or support. You can reach us via email, phone, or our contact form."
                ],
                "ar"         => [
                    "title"   => "اتصل بنا",
                    "content" => "لا تتردد في التواصل مع شركة أوامر الشبكة للبرمجيات لأي استفسارات أو دعم. يمكنك التواصل معنا عبر البريد الإلكتروني أو الهاتف أو نموذج الاتصال."
                ],
            ],
        ];

        foreach ($pages as $pageData) {
            Page::create($pageData);
        }
    }
}
