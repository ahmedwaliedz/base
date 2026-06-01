<?php

declare(strict_types=1);

namespace Tests\Feature\Admin;

use App\Models\Admin;
use App\Models\Seo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SeoCreateTest extends TestCase
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

    private function validSeoData(array $overrides = []): array
    {
        return array_merge([
            'ar' => [
                'meta_title' => 'العنوان التجريبي',
                'meta_description' => 'وصف تجريبي',
                'meta_keywords' => 'كلمة, كلمة',
            ],
            'en' => [
                'meta_title' => 'Test Meta Title',
                'meta_description' => 'Test meta description content',
                'meta_keywords' => 'keyword1, keyword2',
            ],
        ], $overrides);
    }

    public function test_store_seo_with_valid_data_succeeds(): void
    {
        $data = $this->validSeoData();

        $response = $this->actingAsSuperAdmin()->postJson('/admin/seo', $data);

        $response->assertStatus(200);
        $this->assertTrue(Seo::whereTranslation('meta_title', 'Test Meta Title')->exists());
    }

    public function test_store_seo_persists_without_seoable(): void
    {
        $data = $this->validSeoData();

        $response = $this->actingAsSuperAdmin()->postJson('/admin/seo', $data);

        $response->assertStatus(200);
        $seo = Seo::whereTranslation('meta_title', 'Test Meta Title')->first();
        $this->assertNotNull($seo);
        $this->assertNull($seo->seoable_type);
        $this->assertNull($seo->seoable_id);
    }

    public function test_store_seo_requires_arabic_translation(): void
    {
        $data = $this->validSeoData(['ar' => '']);

        $response = $this->actingAsSuperAdmin()->postJson('/admin/seo', $data);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['ar']);
    }

    public function test_store_seo_requires_english_translation(): void
    {
        $data = $this->validSeoData(['en' => '']);

        $response = $this->actingAsSuperAdmin()->postJson('/admin/seo', $data);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['en']);
    }

    public function test_store_seo_requires_arabic_meta_title(): void
    {
        $data = $this->validSeoData(['ar' => ['meta_description' => 'desc', 'meta_keywords' => 'kw']]);

        $response = $this->actingAsSuperAdmin()->postJson('/admin/seo', $data);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['ar.meta_title']);
    }

    public function test_store_seo_requires_english_meta_title(): void
    {
        $data = $this->validSeoData(['en' => ['meta_description' => 'desc', 'meta_keywords' => 'kw']]);

        $response = $this->actingAsSuperAdmin()->postJson('/admin/seo', $data);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['en.meta_title']);
    }

    public function test_update_seo_with_valid_data_succeeds(): void
    {
        $seo = Seo::create([
            'en' => ['meta_title' => 'Original', 'meta_description' => 'Original desc', 'meta_keywords' => 'orig'],
            'ar' => ['meta_title' => 'أصلي', 'meta_description' => 'وصف أصلي', 'meta_keywords' => 'أصلي'],
        ]);
        $data = $this->validSeoData([
            'en' => ['meta_title' => 'Updated Title', 'meta_description' => 'Updated desc', 'meta_keywords' => 'updated'],
        ]);

        $response = $this->actingAsSuperAdmin()->putJson("/admin/seo/{$seo->id}", $data);

        $response->assertStatus(200);
        $seo->refresh();
        $this->assertEquals('Updated Title', $seo->translate('en')->meta_title);
    }

    public function test_update_missing_seo_returns_error(): void
    {
        $response = $this->actingAsSuperAdmin()->putJson('/admin/seo/99999', $this->validSeoData());

        $response->assertStatus(404);
    }

    public function test_delete_existing_seo_succeeds(): void
    {
        $seo = Seo::create([
            'en' => ['meta_title' => 'To Delete', 'meta_description' => 'desc', 'meta_keywords' => 'kw'],
            'ar' => ['meta_title' => 'للحذف', 'meta_description' => 'وصف', 'meta_keywords' => 'كلمة'],
        ]);

        $response = $this->actingAsSuperAdmin()->deleteJson("/admin/seo/{$seo->id}");

        $response->assertStatus(200);
        $this->assertNull(Seo::find($seo->id));
    }

    public function test_store_seo_with_null_image_succeeds(): void
    {
        $data = $this->validSeoData();

        $response = $this->actingAsSuperAdmin()->postJson('/admin/seo', $data);

        $response->assertStatus(200);
        $seo = Seo::whereTranslation('meta_title', 'Test Meta Title')->first();
        $this->assertNotNull($seo);
        $this->assertNotNull($seo->image);
    }

    public function test_seo_show_page_renders_without_seoable(): void
    {
        $seo = Seo::create([
            'en' => ['meta_title' => 'Show Test', 'meta_description' => 'desc', 'meta_keywords' => 'kw'],
            'ar' => ['meta_title' => 'اختبار العرض', 'meta_description' => 'وصف', 'meta_keywords' => 'كلمة'],
        ]);

        $response = $this->actingAsSuperAdmin()->get("/admin/seo/{$seo->id}");

        $response->assertStatus(200);
        $response->assertSee('SEO');
    }
}
