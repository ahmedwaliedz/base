<?php

declare(strict_types=1);

namespace Tests\Feature\Admin;

use App\Models\Admin;
use App\Models\Country;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class BooleanValidationTest extends TestCase
{
    use RefreshDatabase;

    private Country $country;
    private Admin $superAdmin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->country = Country::create(['code' => '20', 'name' => 'Egypt', 'name_ar' => 'مصر', 'is_active' => true]);
        $this->superAdmin = Admin::factory()->create();
    }

    public static function validBooleanProvider(): array
    {
        return [
            'true string' => ['true', true],
            'false string' => ['false', false],
            'on string' => ['on', true],
            'off string' => ['off', false],
            'yes string' => ['yes', true],
            'no string' => ['no', false],
            'integer 1' => [1, true],
            'integer 0' => [0, false],
        ];
    }

    public static function invalidBooleanProvider(): array
    {
        return [
            'maybe' => ['maybe'],
            'unknown' => ['unknown'],
            'random text' => ['random'],
            'maybe123' => ['maybe123'],
        ];
    }

    private function assertIsNotifyPersisted(string $model, int $id, bool $expected): void
    {
        $instance = $model === Admin::class ? Admin::find($id) : User::find($id);
        $this->assertSame($expected, $instance->is_notify, "is_notify should be {$expected}");
    }

    private function adminBaseData(): array
    {
        return [
            'name' => 'Test Admin',
            'email' => 'test' . time() . uniqid() . '@example.com',
            'country_code' => $this->country->code,
            'phone' => '12345678901',
            'password' => 'password',
            'type' => 'super_admin',
        ];
    }

    private function userBaseData(): array
    {
        return [
            'name' => 'Test User',
            'email' => 'test' . time() . uniqid() . '@example.com',
            'country_code' => $this->country->code,
            'phone' => '12345678901',
            'password' => 'password',
        ];
    }

    private function adminUpdateData(): array
    {
        return [
            'name' => 'Updated Admin',
            'email' => 'updated' . time() . uniqid() . '@example.com',
            'country_code' => $this->country->code,
            'phone' => '12345678902',
            'type' => 'super_admin',
        ];
    }

    private function userUpdateData(): array
    {
        return [
            'name' => 'Updated User',
            'email' => 'updated' . time() . uniqid() . '@example.com',
            'country_code' => $this->country->code,
            'phone' => '12345678902',
        ];
    }

    private function profileUpdateData(): array
    {
        return [
            'name' => 'Updated Profile',
            'email' => 'profile' . time() . uniqid() . '@example.com',
            'country_code' => $this->country->code,
            'phone' => '12345678903',
        ];
    }

    private function actingAsSuperAdmin(): static
    {
        return $this->actingAs($this->superAdmin, 'admin');
    }

    // ============================================================
    // ADMIN STORE
    // ============================================================

    #[DataProvider('validBooleanProvider')]
    public function test_admin_store_accepts_valid_boolean_and_persists(mixed $value, bool $expected): void
    {
        $data = $this->adminBaseData();
        $data['is_notify'] = $value;

        $response = $this->actingAsSuperAdmin()->postJson('/admin/admins', $data);

        $this->assertTrue(in_array($response->status(), [200, 302]));

        $adminId = Admin::where('email', $data['email'])->first()?->id;
        $this->assertNotNull($adminId, 'Admin should be created');
        $this->assertIsNotifyPersisted(Admin::class, $adminId, $expected);
    }

    #[DataProvider('invalidBooleanProvider')]
    public function test_admin_store_rejects_invalid_boolean(mixed $value): void
    {
        $data = $this->adminBaseData();
        $data['is_notify'] = $value;

        $response = $this->actingAsSuperAdmin()->postJson('/admin/admins', $data);

        $response->assertStatus(422)->assertJsonValidationErrors(['is_notify']);
    }

    public function test_admin_store_null_is_notify_does_not_write_null(): void
    {
        $data = $this->adminBaseData();
        unset($data['is_notify']);

        $response = $this->actingAsSuperAdmin()->postJson('/admin/admins', $data);

        $this->assertTrue(in_array($response->status(), [200, 302]));

        $admin = Admin::where('email', $data['email'])->first();
        $this->assertNotNull($admin);
        $this->assertNotNull($admin->is_notify);
    }

    public function test_admin_store_empty_is_notify_does_not_write_null(): void
    {
        $data = $this->adminBaseData();
        $data['is_notify'] = '';

        $response = $this->actingAsSuperAdmin()->postJson('/admin/admins', $data);

        $this->assertTrue(in_array($response->status(), [200, 302]));

        $admin = Admin::where('email', $data['email'])->first();
        $this->assertNotNull($admin);
        $this->assertNotNull($admin->is_notify);
    }

    // ============================================================
    // ADMIN UPDATE
    // ============================================================

    #[DataProvider('validBooleanProvider')]
    public function test_admin_update_accepts_valid_boolean_and_persists(mixed $value, bool $expected): void
    {
        $admin = Admin::factory()->create(['country_code' => $this->country->code]);
        $data = $this->adminUpdateData();
        $data['is_notify'] = $value;

        $response = $this->actingAsSuperAdmin()->putJson("/admin/admins/{$admin->id}", $data);

        $this->assertTrue(in_array($response->status(), [200, 302]));
        $this->assertIsNotifyPersisted(Admin::class, $admin->id, $expected);
    }

    #[DataProvider('invalidBooleanProvider')]
    public function test_admin_update_rejects_invalid_boolean(mixed $value): void
    {
        $admin = Admin::factory()->create(['country_code' => $this->country->code]);
        $data = $this->adminUpdateData();
        $data['is_notify'] = $value;

        $response = $this->actingAsSuperAdmin()->putJson("/admin/admins/{$admin->id}", $data);

        $response->assertStatus(422)->assertJsonValidationErrors(['is_notify']);
    }

    // ============================================================
    // USER STORE
    // ============================================================

    #[DataProvider('validBooleanProvider')]
    public function test_user_store_accepts_valid_boolean_and_persists(mixed $value, bool $expected): void
    {
        $data = $this->userBaseData();
        $data['is_notify'] = $value;

        $response = $this->actingAsSuperAdmin()->postJson('/admin/users', $data);

        $this->assertTrue(in_array($response->status(), [200, 302]));

        $userId = User::where('email', $data['email'])->first()?->id;
        $this->assertNotNull($userId, 'User should be created');
        $this->assertIsNotifyPersisted(User::class, $userId, $expected);
    }

    #[DataProvider('invalidBooleanProvider')]
    public function test_user_store_rejects_invalid_boolean(mixed $value): void
    {
        $data = $this->userBaseData();
        $data['is_notify'] = $value;

        $response = $this->actingAsSuperAdmin()->postJson('/admin/users', $data);

        $response->assertStatus(422)->assertJsonValidationErrors(['is_notify']);
    }

    public function test_user_store_null_is_notify_does_not_write_null(): void
    {
        $data = $this->userBaseData();
        unset($data['is_notify']);

        $response = $this->actingAsSuperAdmin()->postJson('/admin/users', $data);

        $this->assertTrue(in_array($response->status(), [200, 302]));

        $user = User::where('email', $data['email'])->first();
        $this->assertNotNull($user);
        $this->assertNotNull($user->is_notify);
    }

    public function test_user_store_empty_is_notify_does_not_write_null(): void
    {
        $data = $this->userBaseData();
        $data['is_notify'] = '';

        $response = $this->actingAsSuperAdmin()->postJson('/admin/users', $data);

        $this->assertTrue(in_array($response->status(), [200, 302]));

        $user = User::where('email', $data['email'])->first();
        $this->assertNotNull($user);
        $this->assertNotNull($user->is_notify);
    }

    // ============================================================
    // USER UPDATE
    // ============================================================

    #[DataProvider('validBooleanProvider')]
    public function test_user_update_accepts_valid_boolean_and_persists(mixed $value, bool $expected): void
    {
        $user = User::factory()->create(['country_code' => $this->country->code]);
        $data = $this->userUpdateData();
        $data['is_notify'] = $value;

        $response = $this->actingAsSuperAdmin()->putJson("/admin/users/{$user->id}", $data);

        $this->assertTrue(in_array($response->status(), [200, 302]));
        $this->assertIsNotifyPersisted(User::class, $user->id, $expected);
    }

    #[DataProvider('invalidBooleanProvider')]
    public function test_user_update_rejects_invalid_boolean(mixed $value): void
    {
        $user = User::factory()->create(['country_code' => $this->country->code]);
        $data = $this->userUpdateData();
        $data['is_notify'] = $value;

        $response = $this->actingAsSuperAdmin()->putJson("/admin/users/{$user->id}", $data);

        $response->assertStatus(422)->assertJsonValidationErrors(['is_notify']);
    }

    // ============================================================
    // PROFILE UPDATE
    // ============================================================

    #[DataProvider('validBooleanProvider')]
    public function test_profile_update_accepts_valid_boolean_and_persists(mixed $value, bool $expected): void
    {
        $data = $this->profileUpdateData();
        $data['is_notify'] = $value;

        $response = $this->actingAsSuperAdmin()->putJson('/admin/profile/update', $data);

        $this->assertEquals(200, $response->status());
        $this->assertIsNotifyPersisted(Admin::class, $this->superAdmin->id, $expected);
    }

    #[DataProvider('invalidBooleanProvider')]
    public function test_profile_update_rejects_invalid_boolean(mixed $value): void
    {
        $data = $this->profileUpdateData();
        $data['is_notify'] = $value;

        $response = $this->actingAsSuperAdmin()->putJson('/admin/profile/update', $data);

        $response->assertStatus(422)->assertJsonValidationErrors(['is_notify']);
    }

    public function test_profile_update_null_is_notify_does_not_write_null(): void
    {
        $data = $this->profileUpdateData();
        unset($data['is_notify']);

        $response = $this->actingAsSuperAdmin()->putJson('/admin/profile/update', $data);

        $this->assertEquals(200, $response->status());

        $this->superAdmin->refresh();
        $this->assertNotNull($this->superAdmin->is_notify);
    }

    public function test_profile_update_empty_is_notify_does_not_write_null(): void
    {
        $data = $this->profileUpdateData();
        $data['is_notify'] = '';

        $response = $this->actingAsSuperAdmin()->putJson('/admin/profile/update', $data);

        $this->assertEquals(200, $response->status());

        $this->superAdmin->refresh();
        $this->assertNotNull($this->superAdmin->is_notify);
    }

    public function test_profile_update_without_is_notify_preserves_existing_value(): void
    {
        $this->superAdmin->update(['is_notify' => false]);

        $data = $this->profileUpdateData();
        unset($data['is_notify']);

        $response = $this->actingAsSuperAdmin()->putJson('/admin/profile/update', $data);

        $this->assertEquals(200, $response->status());

        $this->superAdmin->refresh();
        $this->assertFalse($this->superAdmin->is_notify);
    }

    public function test_profile_update_without_is_notify_preserves_existing_true(): void
    {
        $this->superAdmin->update(['is_notify' => true]);

        $data = $this->profileUpdateData();
        unset($data['is_notify']);

        $response = $this->actingAsSuperAdmin()->putJson('/admin/profile/update', $data);

        $this->assertEquals(200, $response->status());

        $this->superAdmin->refresh();
        $this->assertTrue($this->superAdmin->is_notify);
    }
}