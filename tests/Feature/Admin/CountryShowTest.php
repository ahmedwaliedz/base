<?php

declare(strict_types=1);

namespace Tests\Feature\Admin;

use App\Models\Admin;
use App\Models\Country;
use App\Models\Region;
use App\Models\City;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CountryShowTest extends TestCase
{
    use RefreshDatabase;

    private Country $country;
    private Admin $superAdmin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->country = Country::create(['code' => '20', 'is_active' => true]);
        $this->superAdmin = Admin::factory()->create();
    }

    private function actingAsSuperAdmin(): static
    {
        return $this->actingAs($this->superAdmin, 'admin');
    }

    public function test_country_show_renders_with_both_translations(): void
    {
        $this->country->translateOrNew('ar')->name = 'مصر';
        $this->country->translateOrNew('en')->name = 'Egypt';
        $this->country->save();

        $response = $this->actingAsSuperAdmin()->get(route('admin.countries.show', $this->country->id));

        $response->assertStatus(200);
        $response->assertSee('مصر');
        $response->assertSee('Egypt');
    }

    public function test_country_show_renders_with_only_arabic_translation(): void
    {
        $this->country->translateOrNew('ar')->name = 'مصر';
        $this->country->save();

        $response = $this->actingAsSuperAdmin()->get(route('admin.countries.show', $this->country->id));

        $response->assertStatus(200);
        $response->assertSee('مصر');
        $response->assertSee('—');
    }

    public function test_country_show_renders_with_only_english_translation(): void
    {
        $this->country->translateOrNew('en')->name = 'Egypt';
        $this->country->save();

        $response = $this->actingAsSuperAdmin()->get(route('admin.countries.show', $this->country->id));

        $response->assertStatus(200);
        $response->assertSee('—');
        $response->assertSee('Egypt');
    }

    public function test_country_show_renders_with_no_translations(): void
    {
        $response = $this->actingAsSuperAdmin()->get(route('admin.countries.show', $this->country->id));

        $response->assertStatus(200);
        $response->assertSee('—');
    }

    public function test_country_show_renders_with_regions(): void
    {
        $region = Region::create(['country_id' => $this->country->id, 'is_active' => true]);
        $region->translateOrNew('ar')->name = 'القاهرة';
        $region->translateOrNew('en')->name = 'Cairo';
        $region->save();

        $response = $this->actingAsSuperAdmin()->get(route('admin.countries.show', $this->country->id));

        $response->assertStatus(200);
        $response->assertSee('القاهرة');
        $response->assertSee('Cairo');
    }

    public function test_country_show_renders_with_cities(): void
    {
        $region = Region::create(['country_id' => $this->country->id, 'is_active' => true]);
        $city = City::create(['country_id' => $this->country->id, 'region_id' => $region->id, 'is_active' => true]);
        $city->translateOrNew('ar')->name = 'مصر الجديدة';
        $city->translateOrNew('en')->name = 'New Cairo';
        $city->save();

        $response = $this->actingAsSuperAdmin()->get(route('admin.countries.show', $this->country->id));

        $response->assertStatus(200);
        $response->assertSee('مصر الجديدة');
        $response->assertSee('New Cairo');
    }

    public function test_country_show_renders_flag_fallback(): void
    {
        $response = $this->actingAsSuperAdmin()->get(route('admin.countries.show', $this->country->id));

        $response->assertStatus(200);
        $response->assertSee('/defaults/default.png');
    }

    public function test_guest_cannot_access_country_show(): void
    {
        $response = $this->get(route('admin.countries.show', $this->country->id));

        $response->assertRedirect(route('admin.loginPage'));
    }
}