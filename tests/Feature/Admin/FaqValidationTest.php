<?php

declare(strict_types=1);

namespace Tests\Feature\Admin;

use App\Enums\FaqType;
use App\Models\Admin;
use App\Models\Faq;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FaqValidationTest extends TestCase
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

    private function createFaq(): Faq
    {
        return Faq::create([
            'type' => FaqType::USER,
            'en' => ['question' => 'Test Question EN', 'answer' => 'Test Answer EN'],
            'ar' => ['question' => 'Test Question AR', 'answer' => 'Test Answer AR'],
        ]);
    }

    private function validFaqData(array $overrides = []): array
    {
        return array_merge([
            'type'      => FaqType::USER->value,
            'is_active' => true,
            'ar'        => ['question' => 'سؤال تجريبي', 'answer' => 'إجابة تجريبية'],
            'en'        => ['question' => 'Test Question', 'answer' => 'Test Answer'],
        ], $overrides);
    }

    public function test_store_faq_with_valid_data_succeeds(): void
    {
        $data = $this->validFaqData();

        $response = $this->actingAsSuperAdmin()->postJson('/admin/faqs', $data);

        $response->assertStatus(200);
        $this->assertTrue(Faq::whereTranslation('question', 'Test Question')->exists());
    }

    public function test_store_faq_requires_type(): void
    {
        $data = $this->validFaqData(['type' => '']);

        $response = $this->actingAsSuperAdmin()->postJson('/admin/faqs', $data);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['type']);
    }

    public function test_store_faq_rejects_invalid_type(): void
    {
        $data = $this->validFaqData(['type' => 'invalid_type']);

        $response = $this->actingAsSuperAdmin()->postJson('/admin/faqs', $data);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['type']);
    }

    public function test_store_faq_requires_arabic_translation(): void
    {
        $data = $this->validFaqData(['ar' => '']);

        $response = $this->actingAsSuperAdmin()->postJson('/admin/faqs', $data);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['ar']);
    }

    public function test_store_faq_requires_english_translation(): void
    {
        $data = $this->validFaqData(['en' => '']);

        $response = $this->actingAsSuperAdmin()->postJson('/admin/faqs', $data);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['en']);
    }

    public function test_store_faq_requires_arabic_question(): void
    {
        $data = $this->validFaqData(['ar' => ['answer' => 'إجابة تجريبية']]);

        $response = $this->actingAsSuperAdmin()->postJson('/admin/faqs', $data);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['ar.question']);
    }

    public function test_store_faq_requires_english_question(): void
    {
        $data = $this->validFaqData(['en' => ['answer' => 'Test Answer']]);

        $response = $this->actingAsSuperAdmin()->postJson('/admin/faqs', $data);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['en.question']);
    }

    public function test_update_faq_with_valid_data_succeeds(): void
    {
        $faq = $this->createFaq();
        $data = $this->validFaqData([
            'type' => FaqType::PROVIDER->value,
            'ar' => ['question' => 'سؤال محدث', 'answer' => 'إجابة محدثة'],
            'en' => ['question' => 'Updated Question', 'answer' => 'Updated Answer'],
        ]);

        $response = $this->actingAsSuperAdmin()->putJson("/admin/faqs/{$faq->id}", $data);

        $response->assertStatus(200);
        $faq->refresh();
        $this->assertEquals('Updated Question', $faq->translate('en')->question);
        $this->assertEquals(FaqType::PROVIDER, $faq->type);
    }

    public function test_update_faq_rejects_invalid_type(): void
    {
        $faq = $this->createFaq();
        $data = $this->validFaqData(['type' => 'bogus']);

        $response = $this->actingAsSuperAdmin()->putJson("/admin/faqs/{$faq->id}", $data);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['type']);
    }

    public function test_update_missing_faq_returns_error(): void
    {
        $response = $this->actingAsSuperAdmin()->putJson('/admin/faqs/99999', $this->validFaqData());

        $response->assertStatus(404);
    }

    public function test_delete_existing_faq_succeeds(): void
    {
        $faq = $this->createFaq();

        $response = $this->actingAsSuperAdmin()->deleteJson("/admin/faqs/{$faq->id}");

        $response->assertStatus(200);
        $this->assertNull(Faq::find($faq->id));
    }

    public function test_store_faq_does_not_accept_slug_or_icon(): void
    {
        $data = $this->validFaqData([
            'slug' => 'should-be-ignored',
            'icon' => 'should-be-ignored',
        ]);

        $response = $this->actingAsSuperAdmin()->postJson('/admin/faqs', $data);

        $response->assertStatus(200);
        $faq = Faq::whereTranslation('question', 'Test Question')->first();
        $this->assertNotNull($faq);
        $this->assertArrayNotHasKey('slug', $faq->getAttributes());
        $this->assertArrayNotHasKey('icon', $faq->getAttributes());
    }

    public function test_store_faq_defaults_is_active_true(): void
    {
        $data = $this->validFaqData();

        $response = $this->actingAsSuperAdmin()->postJson('/admin/faqs', $data);

        $response->assertStatus(200);
        $faq = Faq::whereTranslation('question', 'Test Question')->first();
        $this->assertNotNull($faq);
        $this->assertTrue((bool) $faq->is_active);
    }

    public function test_store_faq_with_is_active_false_persists(): void
    {
        $data = $this->validFaqData(['is_active' => false]);

        $response = $this->actingAsSuperAdmin()->postJson('/admin/faqs', $data);

        $response->assertStatus(200);
        $faq = Faq::whereTranslation('question', 'Test Question')->first();
        $this->assertNotNull($faq);
        $this->assertFalse((bool) $faq->is_active);
    }

    public function test_toggle_faq_is_active_cycles(): void
    {
        $faq = $this->createFaq();
        $this->assertTrue((bool) $faq->is_active);

        $response = $this->actingAsSuperAdmin()->putJson("/admin/faqs/{$faq->id}/switch-is-active");

        $response->assertStatus(200);
        $response->assertJsonStructure(['status', 'message', 'data' => ['is_active']]);
        $firstActive = $response->json('data.is_active');
        $this->assertIsBool($firstActive);

        $faq->refresh();
        $this->assertFalse((bool) $faq->is_active);

        $response = $this->actingAsSuperAdmin()->putJson("/admin/faqs/{$faq->id}/switch-is-active");

        $response->assertStatus(200);
        $faq->refresh();
        $this->assertTrue((bool) $faq->is_active);
    }

    public function test_guest_cannot_toggle_faq_is_active(): void
    {
        $faq = $this->createFaq();

        $response = $this->putJson("/admin/faqs/{$faq->id}/switch-is-active");

        $response->assertStatus(401);
    }

    public function test_store_faq_without_is_active_fails_validation(): void
    {
        $data = $this->validFaqData();
        unset($data['is_active']);

        $response = $this->actingAsSuperAdmin()->postJson('/admin/faqs', $data);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['is_active']);
    }

    public function test_delete_missing_faq_returns_404(): void
    {
        $response = $this->actingAsSuperAdmin()->deleteJson('/admin/faqs/99999');

        $response->assertStatus(404);
    }

    public function test_update_faq_without_is_active_fails_validation(): void
    {
        $faq = $this->createFaq();
        $data = $this->validFaqData();
        unset($data['is_active']);

        $response = $this->actingAsSuperAdmin()->putJson("/admin/faqs/{$faq->id}", $data);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['is_active']);
    }

    public function test_faq_filter_active_only_returns_active_faqs(): void
    {
        Faq::create(['type' => FaqType::USER, 'is_active' => true, 'en' => ['question' => 'Active Q1', 'answer' => 'A1'], 'ar' => ['question' => 'نشط1', 'answer' => 'ج1']]);
        Faq::create(['type' => FaqType::USER, 'is_active' => true, 'en' => ['question' => 'Active Q2', 'answer' => 'A2'], 'ar' => ['question' => 'نشط2', 'answer' => 'ج2']]);
        Faq::create(['type' => FaqType::USER, 'is_active' => false, 'en' => ['question' => 'Inactive Q3', 'answer' => 'A3'], 'ar' => ['question' => 'غير نشط3', 'answer' => 'ج3']]);

        $response = $this->actingAsSuperAdmin()
            ->withHeaders(['X-Requested-With' => 'XMLHttpRequest'])
            ->get('/admin/faqs?filters[is_active]=active_only');

        $response->assertStatus(200);
        $content = $response->getContent();
        $this->assertStringContainsString('نشط1', $content);
        $this->assertStringContainsString('نشط2', $content);
        $this->assertStringNotContainsString('غير نشط3', $content);
    }

    public function test_faq_filter_inactive_only_returns_inactive_faqs(): void
    {
        Faq::create(['type' => FaqType::USER, 'is_active' => true, 'en' => ['question' => 'Active Q1', 'answer' => 'A1'], 'ar' => ['question' => 'نشط1', 'answer' => 'ج1']]);
        Faq::create(['type' => FaqType::USER, 'is_active' => false, 'en' => ['question' => 'Inactive Q2', 'answer' => 'A2'], 'ar' => ['question' => 'غير نشط2', 'answer' => 'ج2']]);
        Faq::create(['type' => FaqType::USER, 'is_active' => false, 'en' => ['question' => 'Inactive Q3', 'answer' => 'A3'], 'ar' => ['question' => 'غير نشط3', 'answer' => 'ج3']]);

        $response = $this->actingAsSuperAdmin()
            ->withHeaders(['X-Requested-With' => 'XMLHttpRequest'])
            ->get('/admin/faqs?filters[is_active]=inactive_only');

        $response->assertStatus(200);
        $content = $response->getContent();
        $this->assertStringNotContainsString('نشط1', $content);
        $this->assertStringContainsString('غير نشط2', $content);
        $this->assertStringContainsString('غير نشط3', $content);
    }
}
