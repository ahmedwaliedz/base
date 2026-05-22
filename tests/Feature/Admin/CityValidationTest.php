<?php

declare(strict_types=1);

namespace Tests\Feature\Admin;

use App\Models\Admin;
use App\Models\City;
use App\Models\Country;
use App\Models\Region;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CityValidationTest extends TestCase
{
    use RefreshDatabase;

    private Admin $superAdmin;
    private Country $country;
    private Region $region;

    protected function setUp(): void
    {
        parent::setUp();
        $this->superAdmin = Admin::factory()->create();
        $this->country = Country::create(['code' => '20', 'is_active' => true]);
        $this->region = Region::create(['country_id' => $this->country->id, 'is_active' => true]);
    }

    private function actingAsSuperAdmin(): static
    {
        return $this->actingAs($this->superAdmin, 'admin');
    }

    private function createCity(): City
    {
        return City::create([
            'country_id' => $this->country->id,
            'region_id' => $this->region->id,
            'is_active' => true,
            'en' => ['name' => 'Test City EN'],
            'ar' => ['name' => 'Test City AR'],
        ]);
    }

    private function validCityData(array $overrides = []): array
    {
        return array_merge([
            'country_id' => $this->country->id,
            'region_id' => $this->region->id,
            'is_active' => true,
            'ar' => ['name' => 'مدينة تجريبية'],
            'en' => ['name' => 'Test City'],
        ], $overrides);
    }

    public function test_store_city_with_valid_data_succeeds(): void
    {
        $data = $this->validCityData();

        $response = $this->actingAsSuperAdmin()->postJson('/admin/cities', $data);

        $response->assertStatus(200);
        $this->assertTrue(City::whereTranslation('name', 'Test City')->exists());
    }

    public function test_store_city_requires_region_id(): void
    {
        $data = $this->validCityData(['region_id' => '']);

        $response = $this->actingAsSuperAdmin()->postJson('/admin/cities', $data);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['region_id']);
    }

    public function test_store_city_with_empty_country_id_derives_from_region(): void
    {
        $data = $this->validCityData(['country_id' => '']);

        $response = $this->actingAsSuperAdmin()->postJson('/admin/cities', $data);

        $response->assertStatus(200);
        $city = City::whereTranslation('name', 'Test City')->first();
        $this->assertNotNull($city);
        $this->assertEquals($this->country->id, $city->country_id);
    }

    public function test_store_city_rejects_invalid_region_id(): void
    {
        $data = $this->validCityData(['region_id' => 99999]);

        $response = $this->actingAsSuperAdmin()->postJson('/admin/cities', $data);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['region_id']);
    }

    public function test_store_city_requires_arabic_translation(): void
    {
        $data = $this->validCityData(['ar' => '']);

        $response = $this->actingAsSuperAdmin()->postJson('/admin/cities', $data);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['ar']);
    }

    public function test_store_city_requires_english_translation(): void
    {
        $data = $this->validCityData(['en' => '']);

        $response = $this->actingAsSuperAdmin()->postJson('/admin/cities', $data);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['en']);
    }

    public function test_update_city_with_valid_data_succeeds(): void
    {
        $city = $this->createCity();
        $data = $this->validCityData([
            'en' => ['name' => 'Updated City'],
            'ar' => ['name' => 'مدينة محدثة'],
        ]);

        $response = $this->actingAsSuperAdmin()->putJson("/admin/cities/{$city->id}", $data);

        $response->assertStatus(200);
        $city->refresh();
        $this->assertEquals('Updated City', $city->translate('en')->name);
    }

    public function test_update_city_requires_region_id(): void
    {
        $city = $this->createCity();
        $data = $this->validCityData(['region_id' => '']);

        $response = $this->actingAsSuperAdmin()->putJson("/admin/cities/{$city->id}", $data);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['region_id']);
    }

    public function test_update_city_rejects_invalid_region_id(): void
    {
        $city = $this->createCity();
        $data = $this->validCityData(['region_id' => 99999]);

        $response = $this->actingAsSuperAdmin()->putJson("/admin/cities/{$city->id}", $data);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['region_id']);
    }

    public function test_update_missing_city_returns_error(): void
    {
        $response = $this->actingAsSuperAdmin()->putJson('/admin/cities/99999', $this->validCityData());

        $response->assertStatus(404);
    }

    public function test_delete_existing_city_succeeds(): void
    {
        $city = $this->createCity();

        $response = $this->actingAsSuperAdmin()->deleteJson("/admin/cities/{$city->id}");

        $response->assertStatus(200);
        $this->assertNull(City::find($city->id));
    }

    public function test_store_city_without_country_id_derives_from_region(): void
    {
        $data = [
            'region_id' => $this->region->id,
            'is_active' => true,
            'ar' => ['name' => 'مدينة من غير دولة'],
            'en' => ['name' => 'City Without Country'],
        ];

        $response = $this->actingAsSuperAdmin()->postJson('/admin/cities', $data);

        $response->assertStatus(200);
        $city = City::whereTranslation('name', 'City Without Country')->first();
        $this->assertNotNull($city);
        $this->assertEquals($this->country->id, $city->country_id);
    }

    public function test_update_city_without_country_id_preserves_derivation(): void
    {
        $city = $this->createCity();
        $newRegion = Region::create(['country_id' => $this->country->id, 'is_active' => true]);

        $data = [
            'region_id' => $newRegion->id,
            'is_active' => true,
            'ar' => ['name' => 'مدينة محدثة'],
            'en' => ['name' => 'Updated City No Country'],
        ];

        $response = $this->actingAsSuperAdmin()->putJson("/admin/cities/{$city->id}", $data);

        $response->assertStatus(200);
        $city->refresh();
        $this->assertEquals($this->country->id, $city->country_id);
        $this->assertEquals($newRegion->id, $city->region_id);
        $this->assertEquals('Updated City No Country', $city->translate('en')->name);
    }

    public function test_store_city_with_mismatched_country_id_is_corrected(): void
    {
        $otherCountry = Country::create(['code' => '1', 'is_active' => true]);
        $data = $this->validCityData(['country_id' => $otherCountry->id]);

        $response = $this->actingAsSuperAdmin()->postJson('/admin/cities', $data);

        $response->assertStatus(200);
        $city = City::whereTranslation('name', 'Test City')->first();
        $this->assertNotNull($city);
        $this->assertEquals($this->country->id, $city->country_id);
    }

    public function test_update_city_with_mismatched_country_id_is_corrected(): void
    {
        $city = $this->createCity();
        $otherCountry = Country::create(['code' => '1', 'is_active' => true]);

        $data = $this->validCityData([
            'country_id' => $otherCountry->id,
            'en' => ['name' => 'Mismatch Corrected'],
            'ar' => ['name' => 'تم التصحيح'],
        ]);

        $response = $this->actingAsSuperAdmin()->putJson("/admin/cities/{$city->id}", $data);

        $response->assertStatus(200);
        $city->refresh();
        $this->assertEquals($this->country->id, $city->country_id);
    }

    public function test_store_city_without_is_active_fails_validation(): void
    {
        $data = $this->validCityData();
        unset($data['is_active']);

        $response = $this->actingAsSuperAdmin()->postJson('/admin/cities', $data);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['is_active']);
    }

    public function test_update_city_without_is_active_fails_validation(): void
    {
        $city = $this->createCity();
        $data = $this->validCityData();
        unset($data['is_active']);

        $response = $this->actingAsSuperAdmin()->putJson("/admin/cities/{$city->id}", $data);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['is_active']);
    }
}
