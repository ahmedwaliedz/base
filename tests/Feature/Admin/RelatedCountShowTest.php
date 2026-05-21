<?php

declare(strict_types=1);

namespace Tests\Feature\Admin;

use App\Models\Admin;
use App\Models\Category;
use App\Models\City;
use App\Models\Country;
use App\Models\District;
use App\Models\Region;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RelatedCountShowTest extends TestCase
{
    use RefreshDatabase;

    private Admin $superAdmin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->superAdmin = Admin::factory()->create();
    }

    private function actingAsSuperAdmin(): static
    {
        return $this->actingAs($this->superAdmin, 'admin');
    }

    public function test_category_show_shows_real_subcategory_count(): void
    {
        $parent = Category::create(['slug' => 'parent', 'is_active' => true]);
        $parent->translateOrNew('en')->name = 'Parent';
        $parent->save();

        $child1 = Category::create(['slug' => 'child-1', 'parent_id' => $parent->id, 'is_active' => true]);
        $child1->translateOrNew('en')->name = 'Child 1';
        $child1->save();

        $child2 = Category::create(['slug' => 'child-2', 'parent_id' => $parent->id, 'is_active' => true]);
        $child2->translateOrNew('en')->name = 'Child 2';
        $child2->save();

        $response = $this->actingAsSuperAdmin()->get(route('admin.categories.show', $parent->id));

        $response->assertStatus(200);
        $response->assertSee('2');
    }

    public function test_region_show_shows_real_city_count(): void
    {
        $country = Country::create(['code' => '20', 'is_active' => true]);
        $region = Region::create(['country_id' => $country->id, 'is_active' => true]);
        $region->translateOrNew('en')->name = 'Test Region';
        $region->save();

        $city1 = City::create(['country_id' => $country->id, 'region_id' => $region->id, 'is_active' => true]);
        $city1->translateOrNew('en')->name = 'City 1';
        $city1->save();

        $city2 = City::create(['country_id' => $country->id, 'region_id' => $region->id, 'is_active' => true]);
        $city2->translateOrNew('en')->name = 'City 2';
        $city2->save();

        $city3 = City::create(['country_id' => $country->id, 'region_id' => $region->id, 'is_active' => true]);
        $city3->translateOrNew('en')->name = 'City 3';
        $city3->save();

        $response = $this->actingAsSuperAdmin()->get(route('admin.regions.show', $region->id));

        $response->assertStatus(200);
        $response->assertSee('3');
    }

    public function test_city_show_shows_real_district_count(): void
    {
        $country = Country::create(['code' => '20', 'is_active' => true]);
        $region = Region::create(['country_id' => $country->id, 'is_active' => true]);
        $region->translateOrNew('en')->name = 'Test Region';
        $region->save();
        $city = City::create(['country_id' => $country->id, 'region_id' => $region->id, 'is_active' => true]);
        $city->translateOrNew('en')->name = 'Test City';
        $city->save();

        $district1 = District::create(['city_id' => $city->id, 'is_active' => true]);
        $district1->translateOrNew('en')->name = 'District 1';
        $district1->save();

        $district2 = District::create(['city_id' => $city->id, 'is_active' => true]);
        $district2->translateOrNew('en')->name = 'District 2';
        $district2->save();

        $response = $this->actingAsSuperAdmin()->get(route('admin.cities.show', $city->id));

        $response->assertStatus(200);
        $response->assertSee('2');
    }
}
