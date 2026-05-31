<?php

declare(strict_types=1);

namespace Tests\Feature\Admin;

use App\Models\Admin;
use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PostValidationTest extends TestCase
{
    use RefreshDatabase;

    private Admin $superAdmin;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
        $this->superAdmin = Admin::factory()->create();
    }

    private function actingAsSuperAdmin(): static
    {
        return $this->actingAs($this->superAdmin, 'admin');
    }

    private function insertPost(array $overrides = []): Post
    {
        $data = array_merge([
            'is_active' => true,
            'image' => 'placeholder.png',
            'en' => ['title' => 'Test Title', 'content' => 'Test Content'],
            'ar' => ['title' => 'عنوان تجريبي', 'content' => 'محتوى تجريبي'],
        ], $overrides);

        $id = DB::table('posts')->insertGetId([
            'image' => $data['image'],
            'is_active' => $data['is_active'],
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        foreach (['en', 'ar'] as $locale) {
            DB::table('post_translations')->insert([
                'post_id' => $id,
                'locale' => $locale,
                'title' => $data[$locale]['title'],
                'content' => $data[$locale]['content'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return Post::find($id);
    }

    private function postStoreData(array $overrides = []): array
    {
        return array_merge([
            'is_active' => true,
            'image' => UploadedFile::fake()->image('post.jpg'),
            'ar' => [
                'title' => 'عنوان تجريبي للمقال',
                'content' => 'محتوى تجريبي للمقال يصف الموضوع بالتفصيل',
            ],
            'en' => [
                'title' => 'Test Post Title',
                'content' => 'Test post content describing the topic in detail',
            ],
        ], $overrides);
    }

    private function postUpdateData(array $overrides = []): array
    {
        return array_merge([
            'is_active' => true,
            'ar' => ['title' => 'عنوان محدث', 'content' => 'محتوى محدث'],
            'en' => ['title' => 'Updated Title', 'content' => 'Updated Content'],
        ], $overrides);
    }

    // ---------------------------------------------------------------
    // STORE — success
    // ---------------------------------------------------------------

    public function test_store_post_with_valid_data_succeeds(): void
    {
        $data = $this->postStoreData();

        $response = $this->actingAsSuperAdmin()->post('/admin/posts', $data);

        $response->assertStatus(200);
        $this->assertTrue(Post::whereTranslation('title', 'Test Post Title')->exists());

        $post = Post::whereTranslation('title', 'Test Post Title')->first();
        $rawImage = DB::table('posts')->where('id', $post->id)->value('image');
        $this->assertNotNull($rawImage);
        Storage::disk('public')->assertExists("uploads/posts/{$rawImage}");
    }

    public function test_store_persists_bilingual_translations(): void
    {
        $this->actingAsSuperAdmin()->post('/admin/posts', $this->postStoreData());

        $post = Post::whereTranslation('title', 'Test Post Title')->first();
        $this->assertNotNull($post);
        $this->assertEquals('Test Post Title', $post->translate('en')->title);
        $this->assertEquals('Test post content describing the topic in detail', $post->translate('en')->content);
        $this->assertEquals('عنوان تجريبي للمقال', $post->translate('ar')->title);
        $this->assertEquals('محتوى تجريبي للمقال يصف الموضوع بالتفصيل', $post->translate('ar')->content);
    }

    public function test_store_post_persists_is_active_true_by_default(): void
    {
        $this->actingAsSuperAdmin()->post('/admin/posts', $this->postStoreData());

        $post = Post::whereTranslation('title', 'Test Post Title')->first();
        $this->assertTrue((bool) $post->is_active);
    }

    public function test_store_post_with_is_active_false_persists(): void
    {
        $data = $this->postStoreData(['is_active' => false]);

        $this->actingAsSuperAdmin()->post('/admin/posts', $data);

        $post = Post::whereTranslation('title', 'Test Post Title')->first();
        $this->assertFalse((bool) $post->is_active);
    }

    // ---------------------------------------------------------------
    // STORE — validation failures
    // ---------------------------------------------------------------

    public function test_store_post_requires_image(): void
    {
        $data = $this->postStoreData();
        unset($data['image']);

        $response = $this->actingAsSuperAdmin()->postJson('/admin/posts', $data);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['image']);
    }

    public function test_store_post_requires_arabic_translation(): void
    {
        $data = $this->postStoreData(['ar' => '']);

        $response = $this->actingAsSuperAdmin()->postJson('/admin/posts', $data);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['ar']);
    }

    public function test_store_post_requires_english_translation(): void
    {
        $data = $this->postStoreData(['en' => '']);

        $response = $this->actingAsSuperAdmin()->postJson('/admin/posts', $data);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['en']);
    }

    public function test_store_post_requires_arabic_title(): void
    {
        $data = $this->postStoreData(['ar' => ['content' => 'محتوى تجريبي']]);

        $response = $this->actingAsSuperAdmin()->postJson('/admin/posts', $data);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['ar.title']);
    }

    public function test_store_post_requires_english_title(): void
    {
        $data = $this->postStoreData(['en' => ['content' => 'Test content']]);

        $response = $this->actingAsSuperAdmin()->postJson('/admin/posts', $data);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['en.title']);
    }

    public function test_store_post_requires_is_active(): void
    {
        $data = $this->postStoreData();
        unset($data['is_active']);

        $response = $this->actingAsSuperAdmin()->postJson('/admin/posts', $data);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['is_active']);
    }

    // ---------------------------------------------------------------
    // UPDATE
    // ---------------------------------------------------------------

    public function test_update_post_without_image_succeeds(): void
    {
        $post = $this->insertPost();
        $data = $this->postUpdateData();

        $response = $this->actingAsSuperAdmin()->put("/admin/posts/{$post->id}", $data);

        $response->assertStatus(200);
        $post->refresh();
        $this->assertEquals('Updated Title', $post->translate('en')->title);
    }

    public function test_update_post_with_new_image_succeeds(): void
    {
        $post = $this->insertPost();
        $data = $this->postUpdateData([
            'image' => UploadedFile::fake()->image('new-post.jpg'),
        ]);

        $response = $this->actingAsSuperAdmin()->put("/admin/posts/{$post->id}", $data);

        $response->assertStatus(200);
        $post->refresh();
        $this->assertEquals('Updated Title', $post->translate('en')->title);

        $rawImage = DB::table('posts')->where('id', $post->id)->value('image');
        $this->assertNotNull($rawImage);
        Storage::disk('public')->assertExists("uploads/posts/{$rawImage}");
    }

    public function test_update_post_persists_bilingual_changes(): void
    {
        $post = $this->insertPost();
        $data = [
            'is_active' => true,
            'ar' => ['title' => 'عنوان محدث', 'content' => 'محتوى محدث بالكامل'],
            'en' => ['title' => 'Fully Updated', 'content' => 'Completely new content'],
        ];

        $response = $this->actingAsSuperAdmin()->put("/admin/posts/{$post->id}", $data);

        $response->assertStatus(200);
        $post->refresh();
        $this->assertEquals('Fully Updated', $post->translate('en')->title);
        $this->assertEquals('Completely new content', $post->translate('en')->content);
        $this->assertEquals('عنوان محدث', $post->translate('ar')->title);
        $this->assertEquals('محتوى محدث بالكامل', $post->translate('ar')->content);
    }

    public function test_update_post_requires_is_active(): void
    {
        $post = $this->insertPost();
        $data = $this->postUpdateData();
        unset($data['is_active']);

        $response = $this->actingAsSuperAdmin()->putJson("/admin/posts/{$post->id}", $data);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['is_active']);
    }

    public function test_update_missing_post_returns_404(): void
    {
        $response = $this->actingAsSuperAdmin()->putJson('/admin/posts/99999', $this->postUpdateData());

        $response->assertStatus(404);
    }

    // ---------------------------------------------------------------
    // DELETE & RESTORE
    // ---------------------------------------------------------------

    public function test_delete_existing_post_succeeds(): void
    {
        $post = $this->insertPost();

        $response = $this->actingAsSuperAdmin()->delete("/admin/posts/{$post->id}");

        $response->assertStatus(200);
        $this->assertNotNull($post->fresh()->deleted_at);
    }

    public function test_restore_deleted_post_succeeds(): void
    {
        $post = $this->insertPost();
        $this->actingAsSuperAdmin()->delete("/admin/posts/{$post->id}");
        $this->assertNotNull($post->fresh()->deleted_at);

        $response = $this->actingAsSuperAdmin()->put("/admin/posts/{$post->id}/restore");

        $response->assertStatus(200);
        $this->assertNull($post->fresh()->deleted_at);
    }

    public function test_delete_missing_post_returns_404(): void
    {
        $response = $this->actingAsSuperAdmin()->deleteJson('/admin/posts/99999');

        $response->assertStatus(404);
    }

    public function test_restore_missing_post_returns_404(): void
    {
        $response = $this->actingAsSuperAdmin()->putJson('/admin/posts/99999/restore');

        $response->assertStatus(404);
    }

    // ---------------------------------------------------------------
    // SWITCH IS ACTIVE
    // ---------------------------------------------------------------

    public function test_toggle_post_is_active_cycles(): void
    {
        $post = $this->insertPost();
        $this->assertTrue((bool) $post->is_active);

        $response = $this->actingAsSuperAdmin()->put("/admin/posts/{$post->id}/switch-is-active");
        $response->assertStatus(200);
        $response->assertJsonStructure(['status', 'message', 'data' => ['is_active']]);
        $this->assertIsBool($response->json('data.is_active'));

        $post->refresh();
        $this->assertFalse((bool) $post->is_active);

        $response = $this->actingAsSuperAdmin()->put("/admin/posts/{$post->id}/switch-is-active");
        $response->assertStatus(200);

        $post->refresh();
        $this->assertTrue((bool) $post->is_active);
    }

    public function test_guest_cannot_toggle_post_is_active(): void
    {
        $post = $this->insertPost();

        $response = $this->put("/admin/posts/{$post->id}/switch-is-active");

        $response->assertRedirect(route('admin.loginPage'));
    }

    public function test_toggle_missing_post_returns_404(): void
    {
        $response = $this->actingAsSuperAdmin()->put('/admin/posts/99999/switch-is-active');

        $response->assertStatus(404);
    }

    // ---------------------------------------------------------------
    // SHOW
    // ---------------------------------------------------------------

    public function test_post_show_renders(): void
    {
        $post = $this->insertPost();

        $response = $this->actingAsSuperAdmin()->get("/admin/posts/{$post->id}");

        $response->assertStatus(200);
        $response->assertSee('عنوان تجريبي');
    }

    // ---------------------------------------------------------------
    // FILTERS
    // ---------------------------------------------------------------

    public function test_filter_posts_by_title(): void
    {
        $this->insertPost(['is_active' => true, 'ar' => ['title' => 'أول مقال', 'content' => 'محتوى أول'], 'en' => ['title' => 'First Post', 'content' => 'First content']]);
        $this->insertPost(['is_active' => true, 'ar' => ['title' => 'ثاني مقال', 'content' => 'محتوى ثاني'], 'en' => ['title' => 'Second Post', 'content' => 'Second content']]);

        $response = $this->actingAsSuperAdmin()
            ->withHeaders(['X-Requested-With' => 'XMLHttpRequest'])
            ->get('/admin/posts?filters[title]=أول');

        $response->assertStatus(200);
        $content = $response->getContent();
        $this->assertStringContainsString('أول مقال', $content);
        $this->assertStringNotContainsString('ثاني مقال', $content);
    }

    public function test_filter_posts_active_only(): void
    {
        $this->insertPost(['is_active' => true, 'ar' => ['title' => 'فعال', 'content' => 'محتوى فعال'], 'en' => ['title' => 'Active', 'content' => 'Active content']]);
        $this->insertPost(['is_active' => false, 'ar' => ['title' => 'معطل', 'content' => 'محتوى معطل'], 'en' => ['title' => 'Inactive', 'content' => 'Inactive content']]);

        $response = $this->actingAsSuperAdmin()
            ->withHeaders(['X-Requested-With' => 'XMLHttpRequest'])
            ->get('/admin/posts?filters[is_active]=active_only');

        $response->assertStatus(200);
        $content = $response->getContent();
        $this->assertStringContainsString('فعال', $content);
        $this->assertStringNotContainsString('معطل', $content);
    }

    public function test_filter_posts_inactive_only(): void
    {
        $this->insertPost(['is_active' => true, 'ar' => ['title' => 'فعال', 'content' => 'محتوى فعال'], 'en' => ['title' => 'Active', 'content' => 'Active content']]);
        $this->insertPost(['is_active' => false, 'ar' => ['title' => 'معطل أول', 'content' => 'محتوى معطل أول'], 'en' => ['title' => 'Inactive One', 'content' => 'Inactive content']]);
        $this->insertPost(['is_active' => false, 'ar' => ['title' => 'معطل ثاني', 'content' => 'محتوى معطل ثاني'], 'en' => ['title' => 'Inactive Two', 'content' => 'Inactive content']]);

        $response = $this->actingAsSuperAdmin()
            ->withHeaders(['X-Requested-With' => 'XMLHttpRequest'])
            ->get('/admin/posts?filters[is_active]=inactive_only');

        $response->assertStatus(200);
        $content = $response->getContent();
        $this->assertStringNotContainsString('فعال', $content);
        $this->assertStringContainsString('معطل أول', $content);
        $this->assertStringContainsString('معطل ثاني', $content);
    }
}
