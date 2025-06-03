<?php

namespace Database\Seeders\Category;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = array_merge(
            $this->getMainCategories(),
            $this->getElectronicsSubcategories(),
            $this->getFashionSubcategories(),
            $this->getHomeDecorSubcategories(),
            $this->getSportsSubcategories(),
            $this->getBooksSubcategories(),
            $this->getBeautySubcategories(),
            $this->getAutomotiveSubcategories(),
            $this->getGroceriesSubcategories()
        );

        foreach ($categories as $data) {
            Category::create($data);
        }
    }

    /**
     * Get main categories data
     *
     * @return array
     */
    private function getMainCategories(): array
    {
        return [
            [
                "slug"       => "electronics",
                "icon"       => "electronics.png",
                "is_active"  => true,
                "parent_id"  => null,
                "en"         => ["name" => "Electronics"],
                "ar"         => ["name" => "إلكترونيات"],
            ],
            [
                "slug"       => "fashion",
                "icon"       => "fashion.png",
                "is_active"  => true,
                "parent_id"  => null,
                "en"         => ["name" => "Fashion"],
                "ar"         => ["name" => "الموضة"],
            ],
            [
                "slug"       => "home-decor",
                "icon"       => "home-decor.png",
                "is_active"  => true,
                "parent_id"  => null,
                "en"         => ["name" => "Home Decor"],
                "ar"         => ["name" => "ديكور المنزل"],
            ],
            [
                "slug"       => "sports",
                "icon"       => "sports.png",
                "is_active"  => true,
                "parent_id"  => null,
                "en"         => ["name" => "Sports"],
                "ar"         => ["name" => "رياضة"],
            ],
            [
                "slug"       => "books",
                "icon"       => "book.png",
                "is_active"  => true,
                "parent_id"  => null,
                "en"         => ["name" => "Books"],
                "ar"         => ["name" => "الكتب"],
            ],
            [
                "slug"       => "beauty",
                "icon"       => "beauty.png",
                "is_active"  => true,
                "parent_id"  => null,
                "en"         => ["name" => "Beauty"],
                "ar"         => ["name" => "الجمال"],
            ],
            [
                "slug"       => "automotive",
                "icon"       => "automotive.png",
                "is_active"  => true,
                "parent_id"  => null,
                "en"         => ["name" => "Automotive"],
                "ar"         => ["name" => "السيارات"],
            ],
            [
                "slug"       => "groceries",
                "icon"       => "groceries.png",
                "is_active"  => true,
                "parent_id"  => null,
                "en"         => ["name" => "Groceries"],
                "ar"         => ["name" => "البقالة"],
            ],
        ];
    }

    /**
     * Get Electronics subcategories data
     *
     * @return array
     */
    private function getElectronicsSubcategories(): array
    {
        return [
            [
                "slug"       => "smartphones",
                "icon"       => "smartphones.png",
                "is_active"  => true,
                "parent_id"  => 1,
                "en"         => ["name" => "Smartphones"],
                "ar"         => ["name" => "الهواتف الذكية"],
            ],
            [
                "slug"       => "laptops",
                "icon"       => "laptop.png",
                "is_active"  => true,
                "parent_id"  => 1,
                "en"         => ["name" => "Laptops"],
                "ar"         => ["name" => "أجهزة الكمبيوتر المحمولة"],
            ],
            [
                "slug"       => "cameras",
                "icon"       => "camera.png",
                "is_active"  => true,
                "parent_id"  => 1,
                "en"         => ["name" => "Cameras"],
                "ar"         => ["name" => "الكاميرات"],
            ],
        ];
    }

    /**
     * Get Fashion subcategories data
     *
     * @return array
     */
    private function getFashionSubcategories(): array
    {
        return [
            [
                "slug"       => "male-clothes",
                "icon"       => "male-clothes.png",
                "is_active"  => true,
                "parent_id"  => 2,
                "en"         => ["name" => "Male Clothes"],
                "ar"         => ["name" => "أزياء الرجال"],
            ],
            [
                "slug"       => "women-fashion",
                "icon"       => "women-fashion.png",
                "is_active"  => true,
                "parent_id"  => 2,
                "en"         => ["name" => "Women's Fashion"],
                "ar"         => ["name" => "أزياء النساء"],
            ],
            [
                "slug"       => "kids-fashion",
                "icon"       => "kids-fashion.png",
                "is_active"  => true,
                "parent_id"  => 2,
                "en"         => ["name" => "Kids Fashion"],
                "ar"         => ["name" => "أزياء الأطفال"],
            ],
        ];
    }

    /**
     * Get Home Decor subcategories data
     *
     * @return array
     */
    private function getHomeDecorSubcategories(): array
    {
        return [
            [
                "slug"       => "furniture",
                "icon"       => "furniture.png",
                "is_active"  => true,
                "parent_id"  => 3,
                "en"         => ["name" => "Furniture"],
                "ar"         => ["name" => "الأثاث"],
            ],
            [
                "slug"       => "lighting",
                "icon"       => "lighting.png",
                "is_active"  => true,
                "parent_id"  => 3,
                "en"         => ["name" => "Lighting"],
                "ar"         => ["name" => "الإضاءة"],
            ],
            [
                "slug"       => "decor-accessories",
                "icon"       => "decor-accessories.png",
                "is_active"  => true,
                "parent_id"  => 3,
                "en"         => ["name" => "Decor Accessories"],
                "ar"         => ["name" => "اكسسوارات الديكور"],
            ],
        ];
    }

    /**
     * Get Sports subcategories data
     *
     * @return array
     */
    private function getSportsSubcategories(): array
    {
        return [
            [
                "slug"       => "fitness",
                "icon"       => "fitness.png",
                "is_active"  => true,
                "parent_id"  => 4,
                "en"         => ["name" => "Fitness"],
                "ar"         => ["name" => "اللياقة البدنية"],
            ],
            [
                "slug"       => "outdoor-sports",
                "icon"       => "outdoor-sports.png",
                "is_active"  => true,
                "parent_id"  => 4,
                "en"         => ["name" => "Outdoor Sports"],
                "ar"         => ["name" => "الرياضات الخارجية"],
            ],
            [
                "slug"       => "team-sports",
                "icon"       => "team-sports.png",
                "is_active"  => true,
                "parent_id"  => 4,
                "en"         => ["name" => "Team Sports"],
                "ar"         => ["name" => "الرياضات الجماعية"],
            ],
        ];
    }

    /**
     * Get Books subcategories data
     *
     * @return array
     */
    private function getBooksSubcategories(): array
    {
        return [
            [
                "slug"       => "fiction",
                "icon"       => "fiction.png",
                "is_active"  => true,
                "parent_id"  => 5,
                "en"         => ["name" => "Fiction"],
                "ar"         => ["name" => "روايات"],
            ],
            [
                "slug"       => "non-fiction",
                "icon"       => "non-fiction.png",
                "is_active"  => true,
                "parent_id"  => 5,
                "en"         => ["name" => "Non-Fiction"],
                "ar"         => ["name" => "كتب تعليمية"],
            ],
            [
                "slug"       => "comics",
                "icon"       => "comics.png",
                "is_active"  => true,
                "parent_id"  => 5,
                "en"         => ["name" => "Comics"],
                "ar"         => ["name" => "القصص المصورة"],
            ],
        ];
    }

    /**
     * Get Beauty subcategories data
     *
     * @return array
     */
    private function getBeautySubcategories(): array
    {
        return [
            [
                "slug"       => "skincare",
                "icon"       => "skincare.png",
                "is_active"  => true,
                "parent_id"  => 6,
                "en"         => ["name" => "Skincare"],
                "ar"         => ["name" => "العناية بالبشرة"],
            ],
            [
                "slug"       => "makeup",
                "icon"       => "makeup.png",
                "is_active"  => true,
                "parent_id"  => 6,
                "en"         => ["name" => "Makeup"],
                "ar"         => ["name" => "المكياج"],
            ],
            [
                "slug"       => "haircare",
                "icon"       => "haircare.png",
                "is_active"  => true,
                "parent_id"  => 6,
                "en"         => ["name" => "Haircare"],
                "ar"         => ["name" => "العناية بالشعر"],
            ],
        ];
    }

    /**
     * Get Automotive subcategories data
     *
     * @return array
     */
    private function getAutomotiveSubcategories(): array
    {
        return [
            [
                "slug"       => "car-accessories",
                "icon"       => "car-accessories.png",
                "is_active"  => true,
                "parent_id"  => 7,
                "en"         => ["name" => "Car Accessories"],
                "ar"         => ["name" => "اكسسوارات السيارات"],
            ],
            [
                "slug"       => "spare-parts",
                "icon"       => "spare-parts.png",
                "is_active"  => true,
                "parent_id"  => 7,
                "en"         => ["name" => "Spare Parts"],
                "ar"         => ["name" => "قطع الغيار"],
            ],
            [
                "slug"       => "motorcycles",
                "icon"       => "motorcycles.png",
                "is_active"  => true,
                "parent_id"  => 7,
                "en"         => ["name" => "Motorcycles"],
                "ar"         => ["name" => "الدراجات النارية"],
            ],
        ];
    }

    /**
     * Get Groceries subcategories data
     *
     * @return array
     */
    private function getGroceriesSubcategories(): array
    {
        return [
            [
                "slug"       => "fresh-produce",
                "icon"       => "fresh-produce.png",
                "is_active"  => true,
                "parent_id"  => 8,
                "en"         => ["name" => "Fresh Produce"],
                "ar"         => ["name" => "منتجات طازجة"],
            ],
            [
                "slug"       => "beverages",
                "icon"       => "beverages.png",
                "is_active"  => true,
                "parent_id"  => 8,
                "en"         => ["name" => "Beverages"],
                "ar"         => ["name" => "المشروبات"],
            ],
            [
                "slug"       => "snacks",
                "icon"       => "snacks.png",
                "is_active"  => true,
                "parent_id"  => 8,
                "en"         => ["name" => "Snacks"],
                "ar"         => ["name" => "الوجبات الخفيفة"],
            ],
        ];
    }
}
