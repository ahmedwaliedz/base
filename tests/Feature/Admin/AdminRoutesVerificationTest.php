<?php

declare(strict_types=1);

namespace Tests\Feature\Admin;

use App\Models\Admin;
use App\Models\Country;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminRoutesVerificationTest extends TestCase
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

    public function test_admin_pages_renders(): void
    {
        $response = $this->actingAsSuperAdmin()->get(route('admin.pages.index'));
        $response->assertStatus(200);
    }

    public function test_admin_sliders_renders(): void
    {
        $response = $this->actingAsSuperAdmin()->get(route('admin.sliders.index'));
        $response->assertStatus(200);
    }

    public function test_admin_faqs_renders(): void
    {
        $response = $this->actingAsSuperAdmin()->get(route('admin.faqs.index'));
        $response->assertStatus(200);
    }

    public function test_admin_posts_renders(): void
    {
        $response = $this->actingAsSuperAdmin()->get(route('admin.posts.index'));
        $response->assertStatus(200);
    }

    public function test_admin_intro_pages_renders(): void
    {
        $response = $this->actingAsSuperAdmin()->get(route('admin.intro-pages.index'));
        $response->assertStatus(200);
    }

    public function test_admin_seo_renders(): void
    {
        $response = $this->actingAsSuperAdmin()->get(route('admin.seo.index'));
        $response->assertStatus(200);
    }

    public function test_admin_socials_renders(): void
    {
        $response = $this->actingAsSuperAdmin()->get(route('admin.socials.index'));
        $response->assertStatus(200);
    }

    public function test_admin_regions_renders(): void
    {
        $country = Country::create(['code' => '20', 'is_active' => true]);
        $response = $this->actingAsSuperAdmin()->get(route('admin.regions.index'));
        $response->assertStatus(200);
    }

    public function test_admin_districts_renders(): void
    {
        $response = $this->actingAsSuperAdmin()->get(route('admin.districts.index'));
        $response->assertStatus(200);
    }

    public function test_admin_contact_messages_renders(): void
    {
        $response = $this->actingAsSuperAdmin()->get(route('admin.contact-messages.index'));
        $response->assertStatus(200);
    }

    public function test_admin_complaints_renders(): void
    {
        $response = $this->actingAsSuperAdmin()->get(route('admin.complaints.index'));
        $response->assertStatus(200);
    }

    public function test_admin_categories_renders(): void
    {
        $response = $this->actingAsSuperAdmin()->get(route('admin.categories.index'));
        $response->assertStatus(200);
    }

    public function test_admin_categories_create_renders(): void
    {
        $response = $this->actingAsSuperAdmin()->get(route('admin.categories.create'));
        $response->assertStatus(200);
    }

    public function test_admin_notifications_renders(): void
    {
        $response = $this->actingAsSuperAdmin()->get(route('admin.notifications.index'));
        $response->assertStatus(200);
    }

    public function test_admin_app_notifications_renders(): void
    {
        $response = $this->actingAsSuperAdmin()->get(route('admin.app-notifications.index'));
        $response->assertStatus(200);
    }

    public function test_admin_users_renders(): void
    {
        $country = Country::create(['code' => '20', 'is_active' => true]);
        $response = $this->actingAsSuperAdmin()->get(route('admin.users.index'));
        $response->assertStatus(200);
    }

    public function test_admin_admins_statistics_renders(): void
    {
        $response = $this->actingAsSuperAdmin()->call('GET', route('admin.admins.statistics'));
        $response->assertStatus(200);
    }

    public function test_admin_countries_renders(): void
    {
        $response = $this->actingAsSuperAdmin()->get(route('admin.countries.index'));
        $response->assertStatus(200);
    }

    public function test_admin_countries_show_renders(): void
    {
        $country = Country::create(['code' => '20', 'is_active' => true]);
        $response = $this->actingAsSuperAdmin()->get(route('admin.countries.show', $country->id));
        $response->assertStatus(200);
    }
}