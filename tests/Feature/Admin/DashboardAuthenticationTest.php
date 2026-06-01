<?php

declare(strict_types=1);

namespace Tests\Feature\Admin;

use App\Models\Admin;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardAuthenticationTest extends TestCase
{
    use RefreshDatabase;

    private Admin $superAdmin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->superAdmin = Admin::factory()->create();
    }

    public function test_login_page_returns_view(): void
    {
        $response = $this->get(route('admin.loginPage'));
        $response->assertStatus(200);
        $response->assertViewIs('admin.auth.login');
    }

    public function test_login_with_valid_credentials(): void
    {
        $admin = Admin::factory()->create([
            'email' => 'test@example.com',
            'password' => 'Password@123',
        ]);

        $response = $this->postJson(route('admin.login'), [
            'email' => 'test@example.com',
            'password' => 'Password@123',
        ]);

        $response->assertStatus(200);
        $this->assertAuthenticated('admin');
    }

    public function test_login_with_invalid_credentials_fails(): void
    {
        $admin = Admin::factory()->create([
            'email' => 'test@example.com',
            'password' => 'Password@123',
        ]);

        $response = $this->postJson(route('admin.login'), [
            'email' => 'test@example.com',
            'password' => 'Wrong@123',
        ]);

        $response->assertStatus(422);
        $this->assertGuest('admin');
    }

    public function test_login_with_nonexistent_email_fails(): void
    {
        $response = $this->postJson(route('admin.login'), [
            'email' => 'nonexistent@example.com',
            'password' => 'Test@12345',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['email']);
        $this->assertGuest('admin');
    }

    public function test_login_with_blocked_admin_fails(): void
    {
        $admin = Admin::factory()->create([
            'email' => 'blocked@example.com',
            'password' => 'Password@123',
            'is_blocked' => true,
        ]);

        $response = $this->postJson(route('admin.login'), [
            'email' => 'blocked@example.com',
            'password' => 'Password@123',
        ]);

        $response->assertStatus(423);
        $this->assertGuest('admin');
    }

    public function test_login_requires_email(): void
    {
        $response = $this->postJson(route('admin.login'), [
            'password' => 'Test@123',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['email']);
    }

    public function test_login_requires_password(): void
    {
        $response = $this->postJson(route('admin.login'), [
            'email' => 'test@example.com',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['password']);
    }

    public function test_logout_redirects_to_login(): void
    {
        $this->actingAs($this->superAdmin, 'admin');

        $response = $this->post(route('admin.logout'));

        $response->assertRedirect(route('admin.loginPage'));
        $this->assertGuest('admin');
    }

    public function test_authenticated_admin_can_access_dashboard(): void
    {
        $response = $this->actingAs($this->superAdmin, 'admin')
            ->get(route('admin.home'));

        $response->assertStatus(200);
    }

    public function test_guest_cannot_access_dashboard(): void
    {
        $response = $this->get(route('admin.home'));

        $response->assertRedirect(route('admin.loginPage'));
    }

    public function test_blocked_admin_cannot_access_dashboard(): void
    {
        $blockedAdmin = Admin::factory()->create([
            'is_blocked' => true,
        ]);

        $response = $this->actingAs($blockedAdmin, 'admin')
            ->get(route('admin.home'));

        $response->assertStatus(403);
    }
}
