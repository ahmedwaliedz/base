<?php

namespace Database\Seeders\MorePage;

use App\Enums\SliderType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SliderSeeder extends Seeder
{

    public function run(): void
    {
        $sliders = [
            [
                "is_active" => true,
                "link"      => "https://example.com/offer1",
                "image"     => "1.png",
                "type"      => SliderType::USER,
                "en"        => [
                    "title"       => "Special Offer",
                    "description" => "Get up to 50% off on selected items."
                ],
                "ar"        => [
                    "title"  => "عرض خاص",
                    "description" => "احصل على خصم يصل إلى 50% على بعض المنتجات."
                ],
            ],
            [
                "is_active" => true,
                "link"      => "https://example.com/new",
                "image"     => "2.png",
                "type"      => SliderType::USER,
                "en"        => [
                    "title"       => "New Arrivals",
                    "description" => "Check out our latest collection."
                ],
                "ar"        => [
                    "title"  => "وصلت حديثاً",
                    "description" => "تصفح تشكيلتنا الجديدة الآن."
                ],
            ],
            [
                "is_active" => false,
                "link"      => "https://example.com/sale",
                "image"     => "3.png",
                "type"      => SliderType::USER,
                "en"        => [
                    "title"       => "Clearance Sale",
                    "description" => "Huge discounts on clearance items."
                ],
                "ar"        => [
                    "title"  => "تخفيضات التصفيات",
                    "description" => "خصومات كبيرة على المنتجات المتوفرة."
                ],
            ],
            [
                "is_active" => true,
                "link"      => "https://example.com/discount",
                "image"     => "4.png",
                "type"      => SliderType::USER,
                "en"        => [
                    "title"       => "Exclusive Discount",
                    "description" => "Enjoy an exclusive discount for our loyal customers."
                ],
                "ar"        => [
                    "title"  => "خصم حصري",
                    "description" => "استمتع بخصم حصري لعملائنا المميزين."
                ],
            ],
            [
                "is_active" => true,
                "link"      => "https://example.com/free-shipping",
                "image"     => "5.png",
                "type"      => SliderType::USER,
                "en"        => [
                    "title"       => "Free Shipping",
                    "description" => "All orders above $100 qualify for free shipping."
                ],
                "ar"        => [
                    "title"  => "شحن مجاني",
                    "description" => "جميع الطلبات التي تتجاوز 100 دولار تحصل على شحن مجاني."
                ],
            ],
        ];
        foreach ($sliders as $sliderData) {
            $id = DB::table('sliders')->insertGetId([
                'image' => $sliderData['image'],
                'link' => $sliderData['link'],
                'type' => $sliderData['type'] instanceof SliderType ? $sliderData['type']->value : $sliderData['type'],
                'is_active' => $sliderData['is_active'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $translations = [];
            foreach (['en', 'ar'] as $locale) {
                $translations[] = [
                    'slider_id' => $id,
                    'locale' => $locale,
                    'title' => $sliderData[$locale]['title'],
                    'description' => $sliderData[$locale]['description'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            DB::table('slider_translations')->insert($translations);
        }
    }
}
