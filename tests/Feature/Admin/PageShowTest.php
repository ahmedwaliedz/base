<?php

declare(strict_types=1);

namespace Tests\Feature\Admin;

use App\Enums\PageType;
use App\Models\Admin;
use App\Models\Page;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PageShowTest extends TestCase
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

    public function test_page_show_renders_without_missing_attributes(): void
    {
        $page = Page::create(['slug' => 'about-us', 'type' => 'public']);
        $page->translateOrNew('ar')->title = 'من نحن';
        $page->translateOrNew('ar')->content = '<p>محتوى صفحة من نحن</p>';
        $page->save();

        $response = $this->actingAsSuperAdmin()->get(route('admin.pages.show', $page->id));

        $response->assertStatus(200);
        $response->assertSee('من نحن');
        $response->assertSee('عام');
        $response->assertSee('about-us');
    }

    public function test_switch_type_cycles_through_enum_values(): void
    {
        $page = Page::create(['slug' => 'test-page', 'type' => PageType::USER->value]);
        $page->translateOrNew('en')->title = 'Test';
        $page->translateOrNew('en')->content = 'Content';
        $page->save();

        $expectedSequence = [PageType::PROVIDER, PageType::PUBLIC, PageType::USER];

        foreach ($expectedSequence as $expected) {
            $response = $this->actingAsSuperAdmin()->put(
                route('admin.pages.switchType', $page->id),
                [],
                ['X-Requested-With' => 'XMLHttpRequest']
            );

            $response->assertStatus(200);
            $response->assertJson(['data' => ['type' => $expected->value]]);

            $page->refresh();
            $this->assertTrue($page->type instanceof PageType);
            $this->assertEquals($expected->value, $page->type->value);
        }
    }

    public function test_guest_cannot_access_page_show(): void
    {
        $page = Page::create(['slug' => 'guest-test', 'type' => 'public']);
        $response = $this->get(route('admin.pages.show', $page->id));
        $response->assertRedirect(route('admin.loginPage'));
    }
}
