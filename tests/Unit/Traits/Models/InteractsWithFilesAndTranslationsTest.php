<?php

declare(strict_types=1);

namespace Tests\Unit\Traits\Models;

use App\Models\Country;
use App\Models\Faq;
use App\Models\Post;
use App\Models\Region;
use App\Models\Role;
use App\Traits\Models\HasConfiguredTranslations;
use App\Traits\Models\InteractsWithFilesAndTranslations;
use App\Traits\Upload\BaseFilesTrait;
use Astrotomic\Translatable\Translatable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class InteractsWithFilesAndTranslationsTest extends TestCase
{
    use RefreshDatabase;

    // ---------------------------------------------------------------
    // Trait usage verification
    // ---------------------------------------------------------------

    public function test_post_uses_interacts_with_files_and_translations(): void
    {
        $uses = class_uses_recursive(Post::class);
        $this->assertContains(InteractsWithFilesAndTranslations::class, $uses);
        $this->assertContains(BaseFilesTrait::class, $uses);
        $this->assertContains(Translatable::class, $uses);
        $this->assertContains(HasConfiguredTranslations::class, $uses);
    }

    public function test_country_uses_interacts_with_files_and_translations(): void
    {
        $uses = class_uses_recursive(Country::class);
        $this->assertContains(InteractsWithFilesAndTranslations::class, $uses);
        $this->assertContains(BaseFilesTrait::class, $uses);
        $this->assertContains(Translatable::class, $uses);
        $this->assertContains(HasConfiguredTranslations::class, $uses);
    }

    public function test_region_uses_plain_translatable(): void
    {
        $uses = class_uses_recursive(Region::class);
        $this->assertContains(Translatable::class, $uses);
        $this->assertContains(HasConfiguredTranslations::class, $uses);
        $this->assertNotContains(InteractsWithFilesAndTranslations::class, $uses);
        $this->assertNotContains(BaseFilesTrait::class, $uses);
    }

    public function test_role_uses_plain_translatable(): void
    {
        $uses = class_uses_recursive(Role::class);
        $this->assertContains(Translatable::class, $uses);
        $this->assertContains(HasConfiguredTranslations::class, $uses);
        $this->assertNotContains(InteractsWithFilesAndTranslations::class, $uses);
        $this->assertNotContains(BaseFilesTrait::class, $uses);
    }

    public function test_slider_uses_interacts_with_files_and_translations(): void
    {
        $uses = class_uses_recursive(\App\Models\Slider::class);
        $this->assertContains(InteractsWithFilesAndTranslations::class, $uses);
        $this->assertContains(BaseFilesTrait::class, $uses);
        $this->assertContains(Translatable::class, $uses);
        $this->assertContains(HasConfiguredTranslations::class, $uses);
    }

    // ---------------------------------------------------------------
    // File+translation model: Post (create/update via Eloquent)
    // ---------------------------------------------------------------

    public function test_post_create_with_file_and_translations(): void
    {
        Storage::fake('public');

        $post = Post::create([
            'image'     => UploadedFile::fake()->image('hero.jpg'),
            'is_active' => true,
            'en'        => ['title' => 'English Title', 'content' => 'English Content'],
            'ar'        => ['title' => 'عنوان عربي', 'content' => 'محتوى عربي'],
        ]);

        $this->assertNotNull($post->id);
        $this->assertTrue($post->is_active);

        // File stored on fake disk
        $filename = $post->getRawOriginal('image');
        $this->assertNotNull($filename);
        $this->assertStringEndsWith('.jpg', $filename);
        Storage::disk('public')->assertExists('uploads/posts/'.$filename);

        // Translations persisted
        $en = DB::table('post_translations')
            ->where('post_id', $post->id)->where('locale', 'en')->first();
        $this->assertEquals('English Title', $en->title);
        $this->assertEquals('English Content', $en->content);

        $ar = DB::table('post_translations')
            ->where('post_id', $post->id)->where('locale', 'ar')->first();
        $this->assertEquals('عنوان عربي', $ar->title);
        $this->assertEquals('محتوى عربي', $ar->content);
    }

    public function test_post_update_replaces_image(): void
    {
        Storage::fake('public');

        $post = Post::create([
            'image'     => UploadedFile::fake()->image('old.jpg'),
            'is_active' => true,
            'en'        => ['title' => 'Title', 'content' => 'Content'],
        ]);

        $oldName = $post->getRawOriginal('image');
        Storage::disk('public')->assertExists('uploads/posts/'.$oldName);

        $post->update([
            'image' => UploadedFile::fake()->image('new.jpg'),
        ]);

        $newName = $post->fresh()->getRawOriginal('image');
        $this->assertNotNull($newName);
        $this->assertStringEndsWith('.jpg', $newName);

        Storage::disk('public')->assertMissing('uploads/posts/'.$oldName);
        Storage::disk('public')->assertExists('uploads/posts/'.$newName);
    }

    public function test_post_image_returns_uploaded_url_when_file_exists(): void
    {
        Storage::fake('public');

        $post = Post::create([
            'image'     => UploadedFile::fake()->image('hero.jpg'),
            'is_active' => true,
            'en'        => ['title' => 'T', 'content' => 'C'],
        ]);

        $filename = $post->getRawOriginal('image');
        $this->assertNotNull($filename);

        $publicDir = public_path('storage/uploads/posts');
        if (! is_dir($publicDir)) {
            mkdir($publicDir, 0755, true);
        }
        file_put_contents($publicDir . '/' . $filename, 'test-content');

        $url = $post->image;
        $this->assertStringContainsString($filename, $url, 'Uploaded image must resolve to the real filename, not the default fallback');
        $this->assertStringNotContainsString('default.png', $url, 'Uploaded image must not resolve to the default fallback');

        unlink($publicDir . '/' . $filename);
    }

    public function test_post_image_url_returns_uploaded_url_when_file_exists(): void
    {
        Storage::fake('public');

        $post = Post::create([
            'image'     => UploadedFile::fake()->image('photo.jpg'),
            'is_active' => true,
            'en'        => ['title' => 'T', 'content' => 'C'],
        ]);

        $filename = $post->getRawOriginal('image');
        $this->assertNotNull($filename);

        $publicDir = public_path('storage/uploads/posts');
        if (! is_dir($publicDir)) {
            mkdir($publicDir, 0755, true);
        }
        file_put_contents($publicDir . '/' . $filename, 'test-content');

        $url = $post->image_url;
        $this->assertStringContainsString($filename, $url, 'image_url must resolve to the real filename, not the default fallback');
        $this->assertStringNotContainsString('default.png', $url, 'image_url must not resolve to the default fallback');

        unlink($publicDir . '/' . $filename);
    }

    public function test_post_image_returns_fallback_when_attribute_empty(): void
    {
        $post = new Post(['is_active' => true]);
        $post->setAttribute('image', null);

        $url = $post->image;
        $this->assertStringContainsString('default.png', $url);
    }

    public function test_post_image_url_returns_fallback_when_attribute_empty(): void
    {
        $post = new Post(['is_active' => true]);
        $post->setAttribute('image', null);

        $url = $post->image_url;
        $this->assertStringContainsString('default.png', $url);
    }

    // ---------------------------------------------------------------
    // File+translation model: Country (create via Eloquent)
    // ---------------------------------------------------------------

    public function test_country_create_with_flag_and_translations(): void
    {
        Storage::fake('public');

        $country = Country::create([
            'code'      => '20',
            'flag'      => UploadedFile::fake()->image('eg.png'),
            'is_active' => true,
            'en'        => ['name' => 'Egypt'],
            'ar'        => ['name' => 'مصر'],
        ]);

        $this->assertNotNull($country->id);
        $this->assertTrue($country->is_active);

        $filename = $country->getRawOriginal('flag');
        $this->assertNotNull($filename);
        Storage::disk('public')->assertExists('uploads/countries/'.$filename);

        $en = DB::table('country_translations')
            ->where('country_id', $country->id)->where('locale', 'en')->first();
        $this->assertEquals('Egypt', $en->name);
    }

    // ---------------------------------------------------------------
    // Country::flag_url uses the custom accessor, not BaseFilesTrait
    // ---------------------------------------------------------------

    public function test_country_flag_url_uses_custom_accessor(): void
    {
        $country = new Country;
        $country->setAttribute('flag', 'test-flag.png');
        $country->setAttribute('is_active', true);
        $country->setAttribute('code', 'XX');

        $ref = new \ReflectionMethod($country, 'getFlagUrlAttribute');
        $result = $country->flag_url;

        $this->assertSame($ref->getDeclaringClass()->getName(), Country::class, 'flag_url must resolve through Country::getFlagUrlAttribute');
        $this->assertIsString($result, 'flag_url should return a string from the custom accessor');
    }

    public function test_country_flag_url_does_not_route_through_file_trait(): void
    {
        $country = new Country;
        $ref = new \ReflectionMethod($country, 'isFileAttributeKey');

        $this->assertFalse($ref->invoke($country, 'flag_url'), 'flag_url must NOT be routed as a file attribute key because Country defines getFlagUrlAttribute');
        $this->assertTrue($ref->invoke($country, 'flag'), 'flag (base key) must still be routed as a file attribute key');
    }

    // ---------------------------------------------------------------
    // Post::image_url falls through to BaseFilesTrait (no custom accessor)
    // ---------------------------------------------------------------

    public function test_post_image_url_falls_through_to_file_trait_when_no_accessor(): void
    {
        $post = new Post;
        $ref = new \ReflectionMethod($post, 'isFileAttributeKey');
        $this->assertTrue($ref->invoke($post, 'image_url'), 'image_url should be routed as a file attribute key when no custom accessor exists');
        $this->assertTrue($ref->invoke($post, 'image'), 'image (base key) should be routed as a file attribute key');
    }

    // ---------------------------------------------------------------
    // Translation-only model: Region (create via Eloquent)
    // ---------------------------------------------------------------

    public function test_region_create_with_translations(): void
    {
        $countryId = DB::table('countries')->insertGetId([
            'code'       => '20',
            'is_active'  => true,
            'flag'       => 'default.png',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $region = Region::create([
            'country_id' => $countryId,
            'is_active'  => true,
            'en'         => ['name' => 'Cairo'],
            'ar'         => ['name' => 'القاهرة'],
        ]);

        $this->assertNotNull($region->id);
        $this->assertTrue($region->is_active);

        $en = DB::table('region_translations')
            ->where('region_id', $region->id)->where('locale', 'en')->first();
        $this->assertEquals('Cairo', $en->name);

        $ar = DB::table('region_translations')
            ->where('region_id', $region->id)->where('locale', 'ar')->first();
        $this->assertEquals('القاهرة', $ar->name);
    }

    // ---------------------------------------------------------------
    // Translation-only model: Faq
    // ---------------------------------------------------------------

    public function test_faq_create_with_translations(): void
    {
        $faq = Faq::create([
            'type'      => 'public',
            'is_active' => true,
            'en'        => ['question' => 'What is this?', 'answer' => 'A test.'],
            'ar'        => ['question' => 'ما هذا؟', 'answer' => 'اختبار.'],
        ]);

        $this->assertNotNull($faq->id);

        $en = DB::table('faq_translations')
            ->where('faq_id', $faq->id)->where('locale', 'en')->first();
        $this->assertEquals('What is this?', $en->question);
        $this->assertEquals('A test.', $en->answer);

        $ar = DB::table('faq_translations')
            ->where('faq_id', $faq->id)->where('locale', 'ar')->first();
        $this->assertEquals('ما هذا؟', $ar->question);
    }

    // ---------------------------------------------------------------
    // HasConfiguredTranslations only declares properties (no methods)
    // ---------------------------------------------------------------

    public function test_has_configured_translations_declares_properties_only(): void
    {
        $ref = new \ReflectionClass(HasConfiguredTranslations::class);

        $props = array_map(fn ($p) => $p->getName(), $ref->getProperties());
        $this->assertContains('localeKey', $props);
        $this->assertContains('translationModel', $props);
        $this->assertContains('translationForeignKey', $props);

        $ownMethods = array_map(fn ($m) => $m->getName(), $ref->getMethods());
        $this->assertNotContains('getLocaleKey', $ownMethods);
        $this->assertNotContains('getTranslationModelName', $ownMethods);
        $this->assertNotContains('getTranslationRelationKey', $ownMethods);
    }

    // ---------------------------------------------------------------
    // Plain translatable model: translation config resolves correctly
    // ---------------------------------------------------------------

    public function test_plain_translatable_model_translation_config_resolves(): void
    {
        $region = new Region;

        $this->assertSame('locale', $region->getLocaleKey());
        $this->assertSame(Region::class . 'Translation', $region->getTranslationModelName());
        $this->assertSame('region_id', $region->getTranslationRelationKey());
    }

    // ---------------------------------------------------------------
    // File+translation model: translation config resolves correctly
    // ---------------------------------------------------------------

    public function test_file_translation_model_translation_config_resolves(): void
    {
        $post = new Post;

        $this->assertSame('locale', $post->getLocaleKey());
        $this->assertSame(Post::class . 'Translation', $post->getTranslationModelName());
        $this->assertSame('post_id', $post->getTranslationRelationKey());
    }

    // ---------------------------------------------------------------
    // Property declarations prevent MissingAttributeException in strict mode
    // ---------------------------------------------------------------

    public function test_configured_translation_properties_are_declared_on_model(): void
    {
        $ref = new \ReflectionClass(Region::class);

        $this->assertTrue($ref->hasProperty('localeKey'));
        $this->assertTrue($ref->hasProperty('translationModel'));
        $this->assertTrue($ref->hasProperty('translationForeignKey'));

        $declaringClass = $ref->getProperty('localeKey')->getDeclaringClass()->getName();
        $this->assertSame(Region::class, $declaringClass);
    }

    public function test_configured_translation_properties_are_declared_on_file_model(): void
    {
        $ref = new \ReflectionClass(Post::class);

        $this->assertTrue($ref->hasProperty('localeKey'));
        $this->assertTrue($ref->hasProperty('translationModel'));
        $this->assertTrue($ref->hasProperty('translationForeignKey'));

        $declaringClass = $ref->getProperty('localeKey')->getDeclaringClass()->getName();
        $this->assertSame(Post::class, $declaringClass);
    }
}
