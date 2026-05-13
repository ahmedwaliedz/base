<?php

declare(strict_types=1);

namespace Tests\Feature\Admin;

use App\Models\Admin;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleValidationTest extends TestCase
{
    use RefreshDatabase;

    private Admin $superAdmin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->superAdmin = Admin::factory()->create();
        Permission::create(['permission' => 'admin.dashboard']);
    }

    private function actingAsSuperAdmin(): static
    {
        return $this->actingAs($this->superAdmin, 'admin');
    }

    private function createRole(): Role
    {
        return Role::create([
            'en' => ['name' => 'Test Role EN'],
            'ar' => ['name' => 'Test Role AR'],
        ]);
    }

    private function validRoleData(array $overrides = []): array
    {
        return array_merge([
            'ar' => ['name' => 'Test Role Arabic'],
            'en' => ['name' => 'Test Role English'],
            'permissions' => ['admin.dashboard'],
        ], $overrides);
    }

    public function test_store_role_with_valid_permissions_succeeds(): void
    {
        $data = $this->validRoleData();

        $response = $this->actingAsSuperAdmin()->postJson('/admin/roles', $data);

        $response->assertStatus(200);
        $this->assertTrue(Role::whereTranslation('name', 'Test Role English')->exists());
    }

    public function test_store_role_with_invalid_permission_rejected(): void
    {
        $data = $this->validRoleData([
            'permissions' => ['admin.dashboard', 'invalid.permission.string'],
        ]);

        $response = $this->actingAsSuperAdmin()->postJson('/admin/roles', $data);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['permissions.1']);
    }

    public function test_update_role_with_valid_permissions_succeeds(): void
    {
        $role = $this->createRole();
        $data = $this->validRoleData([
            'ar' => ['name' => 'Updated Role Arabic'],
            'en' => ['name' => 'Updated Role English'],
        ]);

        $response = $this->actingAsSuperAdmin()->putJson("/admin/roles/{$role->id}", $data);

        $response->assertStatus(200);
        $role->refresh();
        $this->assertEquals('Updated Role Arabic', $role->translate('ar')->name);
    }

    public function test_update_role_with_invalid_permission_rejected(): void
    {
        $role = $this->createRole();
        $data = $this->validRoleData([
            'permissions' => ['admin.dashboard', 'totally.invalid.permission'],
        ]);

        $response = $this->actingAsSuperAdmin()->putJson("/admin/roles/{$role->id}", $data);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['permissions.1']);
    }

    public function test_update_missing_role_returns_404(): void
    {
        $response = $this->actingAsSuperAdmin()->putJson('/admin/roles/99999', $this->validRoleData());

        $response->assertStatus(404);
    }

    public function test_delete_missing_role_returns_404(): void
    {
        $response = $this->actingAsSuperAdmin()->deleteJson('/admin/roles/99999');

        $response->assertStatus(404);
    }

    public function test_delete_existing_role_succeeds(): void
    {
        $role = $this->createRole();

        $response = $this->actingAsSuperAdmin()->deleteJson("/admin/roles/{$role->id}");

        $response->assertStatus(200);
        $this->assertNull(Role::find($role->id));
    }

    public function test_store_role_with_existing_db_permission_succeeds(): void
    {
        $permission = Permission::create(['permission' => 'custom.existing.permission']);

        $data = $this->validRoleData([
            'permissions' => ['admin.dashboard', 'custom.existing.permission'],
        ]);

        $response = $this->actingAsSuperAdmin()->postJson('/admin/roles', $data);

        $response->assertStatus(200);
    }

    public function test_service_level_invalid_permissions_return_422_not_500(): void
    {
        $this->superAdmin->role_id = null;
        $this->superAdmin->save();

        $response = $this->actingAsSuperAdmin()->postJson('/admin/roles', [
            'ar' => ['name' => 'Test Role'],
            'en' => ['name' => 'Test Role'],
            'permissions' => ['this.is.definitely.not.valid'],
        ]);

        $response->assertStatus(422);
        $response->assertJsonStructure(['message', 'errors']);
        $response->assertJsonFragment(['message' => __('admin/validation.invalid_permission')]);
    }
}