<?php

declare(strict_types=1);

namespace Tests\Feature\Admin;

use App\Models\Admin;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryValidationTest extends TestCase
{
    use RefreshDatabase;

    private Admin $superAdmin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->superAdmin = Admin::factory()->create(['role_id' => null]);
    }

    private function actingAsSuperAdmin(): static
    {
        return $this->actingAs($this->superAdmin, 'admin');
    }

    private function createCategory(array $overrides = []): Category
    {
        $slug = 'cat-' . uniqid();
        $category = Category::create(array_merge([
            'slug' => $slug,
            'is_active' => true,
        ], $overrides));
        $category->translateOrNew('en')->name = 'Test EN ' . $slug;
        $category->translateOrNew('ar')->name = 'اختبار ' . $slug;
        $category->save();

        return $category;
    }

    private function validUpdateData(array $overrides = []): array
    {
        return array_merge([
            'slug' => 'updated-' . uniqid(),
            'is_active' => true,
            'ar' => ['name' => 'تصنيف محدث'],
            'en' => ['name' => 'Updated Category'],
        ], $overrides);
    }

    private function validStoreData(array $overrides = []): array
    {
        return array_merge([
            'slug' => 'new-' . uniqid(),
            'is_active' => true,
            'ar' => ['name' => 'تصنيف جديد'],
            'en' => ['name' => 'New Category'],
        ], $overrides);
    }

    public function test_update_category_rejects_self_as_parent(): void
    {
        $category = $this->createCategory();

        $data = $this->validUpdateData(['parent_id' => $category->id]);

        $response = $this->actingAsSuperAdmin()->putJson(
            "/admin/categories/{$category->id}",
            $data
        );

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['parent_id']);
    }

    public function test_update_category_accepts_different_parent(): void
    {
        $parent = $this->createCategory();
        $child = $this->createCategory(['parent_id' => null]);

        $data = $this->validUpdateData(['parent_id' => $parent->id]);

        $response = $this->actingAsSuperAdmin()->putJson(
            "/admin/categories/{$child->id}",
            $data
        );

        $response->assertStatus(200);
    }

    public function test_update_category_accepts_null_parent(): void
    {
        $category = $this->createCategory();

        $data = $this->validUpdateData(['parent_id' => null]);

        $response = $this->actingAsSuperAdmin()->putJson(
            "/admin/categories/{$category->id}",
            $data
        );

        $response->assertStatus(200);
    }

    public function test_update_category_rejects_non_root_as_parent(): void
    {
        $parent = $this->createCategory();
        $child = $this->createCategory(['parent_id' => $parent->id]);
        $other = $this->createCategory();

        $data = $this->validUpdateData(['parent_id' => $child->id]);

        $response = $this->actingAsSuperAdmin()->putJson(
            "/admin/categories/{$other->id}",
            $data
        );

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['parent_id']);
    }

    public function test_store_category_accepts_null_parent(): void
    {
        $data = $this->validStoreData(['parent_id' => null]);

        $response = $this->actingAsSuperAdmin()->postJson('/admin/categories', $data);

        $response->assertStatus(200);
    }

    public function test_store_category_accepts_root_parent(): void
    {
        $root = $this->createCategory();

        $data = $this->validStoreData(['parent_id' => $root->id]);

        $response = $this->actingAsSuperAdmin()->postJson('/admin/categories', $data);

        $response->assertStatus(200);
    }

    public function test_store_category_rejects_non_root_as_parent(): void
    {
        $parent = $this->createCategory();
        $child = $this->createCategory(['parent_id' => $parent->id]);

        $data = $this->validStoreData(['parent_id' => $child->id]);

        $response = $this->actingAsSuperAdmin()->postJson('/admin/categories', $data);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['parent_id']);
    }
}