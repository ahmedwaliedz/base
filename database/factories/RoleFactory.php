<?php

namespace Database\Factories;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Eloquent\Factories\Factory;
use Faker\Factory as FakerFactory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Role>
 */
class RoleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $fakerEn = FakerFactory::create('en_US'); // English Faker
        $fakerAr = FakerFactory::create('ar_SA'); // Arabic Faker
        $arabicJobTitles = [
            'مدير', 'مشرف', 'مهندس', 'محاسب', 'مبرمج', 'مدير مشروع', 'مصمم', 'أخصائي تسويق'
        ];
        return [
                'en' => ['name' => $fakerEn->jobTitle],
                'ar' => ['name' => $fakerAr->randomElement($arabicJobTitles),], // Use Faker Arabic provider if needed
        ];
    }
    public function configure()
    {
        return $this->afterCreating(function (Role $role) {
            $permissions = [
                ['name' =>'admins.admins.create'],
                ['name' =>'admins.admins.store'],
                ['name' =>'admins.admins.edit'],
            ];
            $role->permissions()->createMany($permissions);
        });
    }
}
