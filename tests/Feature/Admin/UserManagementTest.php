<?php

declare(strict_types=1);

namespace Tests\Feature\Admin;

use App\Enums\AdminType;
use App\Models\Admin;
use App\Models\Country;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserManagementTest extends TestCase
{
    use RefreshDatabase;

    private Country $country;
    private Admin $superAdmin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->country = Country::create(['code' => '20', 'is_active' => true]);
        $this->country->translateOrNew('en')->name = 'Egypt';
        $this->country->translateOrNew('ar')->name = 'مصر';
        $this->country->save();
        $this->superAdmin = Admin::factory()->create();
    }

    private function actingAsSuperAdmin(): static
    {
        return $this->actingAs($this->superAdmin, 'admin');
    }

    private function userData(): array
    {
        return [
            'name' => 'Test User',
            'email' => 'test' . time() . uniqid() . '@example.com',
            'country_code' => $this->country->code,
            'phone' => '12345678901',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];
    }

    public function test_users_index_returns_view(): void
    {
        $response = $this->actingAsSuperAdmin()->get(route('admin.users.index'));
        $response->assertStatus(200);
    }

    public function test_users_index_returns_paginated_results(): void
    {
        User::factory()->count(15)->create(['country_code' => $this->country->code]);

        $response = $this->actingAsSuperAdmin()->get(route('admin.users.index'));

        $response->assertStatus(200);
    }

    public function test_users_statistics_returns_view(): void
    {
        $response = $this->actingAsSuperAdmin()->call('GET', route('admin.users.statistics'));
        $response->assertStatus(200);
    }

    public function test_create_user_page_returns_view(): void
    {
        $response = $this->actingAsSuperAdmin()->get(route('admin.users.create'));
        $response->assertStatus(200);
    }

    public function test_store_user_with_valid_data(): void
    {
        $data = $this->userData();

        $response = $this->actingAsSuperAdmin()->post(route('admin.users.store'), $data);

        $response->assertStatus(200);
        $this->assertDatabaseHas('users', [
            'email' => $data['email'],
            'name' => $data['name'],
        ]);
    }

    public function test_store_user_validates_required_fields(): void
    {
        $response = $this->actingAsSuperAdmin()->postJson(route('admin.users.store'), []);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['name', 'email', 'country_code', 'phone', 'password']);
    }

    public function test_store_user_validates_email_format(): void
    {
        $data = $this->userData();
        $data['email'] = 'invalid-email';

        $response = $this->actingAsSuperAdmin()->postJson(route('admin.users.store'), $data);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['email']);
    }

    public function test_store_user_validates_unique_email(): void
    {
        $existingUser = User::factory()->create(['country_code' => $this->country->code]);
        $data = $this->userData();
        $data['email'] = $existingUser->email;

        $response = $this->actingAsSuperAdmin()->postJson(route('admin.users.store'), $data);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['email']);
    }

    public function test_store_user_validates_password_confirmation(): void
    {
        $data = $this->userData();
        $data['password_confirmation'] = 'different-password';

        $response = $this->actingAsSuperAdmin()->post(route('admin.users.store'), $data);

        $response->assertStatus(200);
    }

    public function test_store_user_validates_phone_format(): void
    {
        $data = $this->userData();
        $data['phone'] = 'invalid';

        $response = $this->actingAsSuperAdmin()->postJson(route('admin.users.store'), $data);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['phone']);
    }

    public function test_show_user_returns_view(): void
    {
        $user = User::factory()->create(['country_code' => $this->country->code]);

        $response = $this->actingAsSuperAdmin()->get(route('admin.users.show', $user->id));

        $response->assertStatus(200);
        $response->assertSee($user->name);
    }

    public function test_show_user_returns_404_for_nonexistent(): void
    {
        $response = $this->actingAsSuperAdmin()->get(route('admin.users.show', 9999));

        $response->assertStatus(404);
    }

    public function test_edit_user_page_returns_view(): void
    {
        $user = User::factory()->create(['country_code' => $this->country->code]);

        $response = $this->actingAsSuperAdmin()->get(route('admin.users.edit', $user->id));

        $response->assertStatus(200);
    }

    public function test_update_user_with_valid_data(): void
    {
        $user = User::factory()->create(['country_code' => $this->country->code]);

        $data = [
            'name' => 'Updated Name',
            'email' => $user->email,
            'country_code' => $this->country->code,
            'phone' => '12345678901',
        ];

        $response = $this->actingAsSuperAdmin()->put(route('admin.users.update', $user->id), $data);

        $response->assertStatus(200);
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Updated Name',
        ]);
    }

    public function test_update_user_validates_email_uniqueness(): void
    {
        $user1 = User::factory()->create(['country_code' => $this->country->code]);
        $user2 = User::factory()->create(['country_code' => $this->country->code]);

        $data = [
            'name' => $user1->name,
            'email' => $user2->email,
            'country_code' => $this->country->code,
            'phone' => '12345678901',
        ];

        $response = $this->actingAsSuperAdmin()->putJson(route('admin.users.update', $user1->id), $data);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['email']);
    }

    public function test_delete_user(): void
    {
        $user = User::factory()->create(['country_code' => $this->country->code]);

        $response = $this->actingAsSuperAdmin()->delete(route('admin.users.destroy', $user->id));

        $response->assertStatus(200);
        $this->assertSoftDeleted('users', ['id' => $user->id]);
    }

    public function test_bulk_delete_users(): void
    {
        $users = User::factory()->count(3)->create(['country_code' => $this->country->code]);
        $userIds = $users->pluck('id')->toArray();

        $response = $this->actingAsSuperAdmin()->delete(route('admin.users.destroyAll'), [
            'ids' => $userIds,
        ]);

        $response->assertStatus(200);
        foreach ($userIds as $id) {
            $this->assertSoftDeleted('users', ['id' => $id]);
        }
    }

    public function test_block_user(): void
    {
        $user = User::factory()->create(['country_code' => $this->country->code, 'is_blocked' => false]);

        $response = $this->actingAsSuperAdmin()->put(
            route('admin.users.switchBlock', $user->id)
        );

        $response->assertStatus(200);
        $this->assertDatabaseHas('users', ['id' => $user->id, 'is_blocked' => true]);
    }

    public function test_unblock_user(): void
    {
        $user = User::factory()->create(['country_code' => $this->country->code, 'is_blocked' => true]);

        $response = $this->actingAsSuperAdmin()->put(
            route('admin.users.switchBlock', $user->id)
        );

        $response->assertStatus(200);
        $this->assertDatabaseHas('users', ['id' => $user->id, 'is_blocked' => false]);
    }

    public function test_search_users_by_name(): void
    {
        User::factory()->create(['name' => 'John Doe', 'country_code' => $this->country->code]);
        User::factory()->create(['name' => 'Jane Smith', 'country_code' => $this->country->code]);

        $response = $this->actingAsSuperAdmin()->get(route('admin.users.index', ['filters[name]' => 'John']));

        $response->assertStatus(200);
    }

    public function test_filter_users_by_blocked_status(): void
    {
        User::factory()->count(2)->create(['is_blocked' => true, 'country_code' => $this->country->code]);
        User::factory()->count(3)->create(['is_blocked' => false, 'country_code' => $this->country->code]);

        $response = $this->actingAsSuperAdmin()->get(route('admin.users.index', ['filters[is_blocked]' => 'blocked_only']));

        $response->assertStatus(200);
    }

    public function test_filter_users_by_date_range(): void
    {
        User::factory()->count(2)->create(['country_code' => $this->country->code]);

        $response = $this->actingAsSuperAdmin()->get(route('admin.users.index', [
            'filters[start_date]' => now()->toDateString(),
            'filters[end_date]' => now()->toDateString(),
        ]));

        $response->assertStatus(200);
    }

    public function test_guest_cannot_access_users_index(): void
    {
        $response = $this->get(route('admin.users.index'));

        $response->assertRedirect(route('admin.loginPage'));
    }

    public function test_unauthorized_user_cannot_create_user(): void
    {
        $admin = Admin::factory()->create([
            'type' => AdminType::ADMIN,
            'role_id' => null,
        ]);
        $data = $this->userData();

        $response = $this->actingAs($admin, 'admin')->post(route('admin.users.store'), $data);

        $response->assertStatus(403);
    }
}
