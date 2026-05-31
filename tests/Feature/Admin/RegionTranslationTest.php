<?php

declare(strict_types=1);

namespace Tests\Feature\Admin;

use App\Models\Admin;
use App\Models\Country;
use App\Models\Region;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegionTranslationTest extends TestCase
{
    use RefreshDatabase;

    private Admin $superAdmin;
    private Country $country;

    protected function setUp(): void
    {
        parent::setUp();
        $this->superAdmin = Admin::factory()->create();
        $this->country = Country::create(['code' => '20', 'is_active' => true]);
    }

    private function actingAsSuperAdmin(): static
    {
        return $this->actingAs($this->superAdmin, 'admin');
    }

    public function test_create_region_persists_both_translations(): void
    {
        $data = [
            'country_id' => $this->country->id,
            'is_active' => true,
            'en' => ['name' => 'Test Region EN'],
            'ar' => ['name' => 'منطقة تجريبية'],
        ];

        $response = $this->actingAsSuperAdmin()->postJson('/admin/regions', $data);

        $response->assertStatus(200);
        $this->assertTrue(Region::whereTranslation('name', 'Test Region EN')->exists());

        $region = Region::whereTranslation('name', 'Test Region EN')->first();
        $this->assertEquals('Test Region EN', $region->translate('en')->name);
        $this->assertEquals('منطقة تجريبية', $region->translate('ar')->name);
    }

    public function test_update_region_updates_translations(): void
    {
        $region = Region::create([
            'country_id' => $this->country->id,
            'is_active' => true,
            'en' => ['name' => 'Original EN'],
            'ar' => ['name' => 'الأصل AR'],
        ]);

        $data = [
            'country_id' => $this->country->id,
            'is_active' => true,
            'en' => ['name' => 'Updated Region EN'],
            'ar' => ['name' => 'المنطقة المحدثة AR'],
        ];

        $response = $this->actingAsSuperAdmin()->putJson("/admin/regions/{$region->id}", $data);

        $response->assertStatus(200);
        $region->refresh();
        $this->assertEquals('Updated Region EN', $region->translate('en')->name);
        $this->assertEquals('المنطقة المحدثة AR', $region->translate('ar')->name);
    }
}
