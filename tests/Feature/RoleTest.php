<?php

namespace Tests\Feature;

use App\Enums\RoleEnum;
use App\Models\Address;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    public function setUp(): void
    {
        parent::setUp();

        $this->seed();

        $this->user = $this->createUser();
    }

    public function test_user_can_see_roles_index(): void
    {
        $response = $this->actingAs($this->user)->get('/users/roles');

        $response->assertStatus(200);
        $response->assertSee('Roles');

        $this->assertDatabaseCount('roles', 3);
    }

    public function test_user_can_get_to_create_role_page(): void
    {
        $response = $this->actingAs($this->user)->get('/users/roles/create');

        $response->assertStatus(200);
        $response->assertSee('Create Role');
    }

    public function test_user_can_store_role(): void
    {
        $role = [
            'name' => 'Publisher',
            'is_active' => true,
            'note' => null,
        ];

        $response = $this->actingAs($this->user)->post('/users/roles', $role);

        $response->assertStatus(302);
        $response->assertRedirect('/users/roles');

        $this->assertDatabaseHas('roles', $role);

        $lastRole = Role::orderByDesc('id')->first();
        $this->assertEquals($role['name'], $lastRole->name);
        $this->assertEquals($role['is_active'], $lastRole->is_active);
    }

    public function test_user_can_store_role_with_permissions(): void
    {
        $permissions = Permission::factory(5)->create();
        $permissionsForRole = $permissions->take(3)->pluck('id');

        $role = [
            'name' => 'Editor',
            'is_active' => true,
            'note' => null,
            'permissions' => $permissionsForRole->toArray(),
        ];

        $response = $this->actingAs($this->user)->post('/users/roles', $role);

        $response->assertStatus(302);
        $response->assertRedirect('/users/roles');

        unset($role['permissions']);
        $this->assertDatabaseHas('roles', $role);

        $lastRole = Role::orderByDesc('id')->first();
        $this->assertEquals($role['name'], $lastRole->name);

        $this->assertDatabaseCount('permission_role', 3);
        $permissionsForRole->each(function (int $permissionId) use ($lastRole) {
            $this->assertDatabaseHas('permission_role', [
                'permission_id' => $permissionId,
                'role_id' => $lastRole->id,
            ]);
        });
    }

    public function test_user_can_get_to_edit_role_page(): void
    {
        $role = Role::factory()->create();

        $response = $this->actingAs($this->user)->get('/users/roles/'.$role->id.'/edit');

        $response->assertStatus(200);
        $response->assertSee('Edit Role');
        $response->assertSee('value="'.$role->name.'"', false);
        $response->assertViewHas('role', $role);
    }

    public function test_user_can_update_role(): void
    {
        $role = Role::factory()->create();
        $editedRole = [
            'name' => 'Design',
            'is_active' => false,
        ];

        $response = $this->actingAs($this->user)->put('/users/roles/'.$role->id, $editedRole);

        $response->assertStatus(302);
        $response->assertRedirect('/users/roles');

        $updatedRole = Role::find($role->id);

        $this->assertEquals($editedRole['name'], $updatedRole->name);
        $this->assertEquals($editedRole['is_active'], $updatedRole->is_active);
    }

    public function test_user_can_update_role_with_permissions(): void
    {
        $permissions = Permission::factory(5)->create();
        $permissionsForRole = $permissions->take(2)->pluck('id');

        $role = Role::factory()->create();
        $editedRole = [
            'name' => 'Design',
            'is_active' => false,
            'permissions' => $permissionsForRole->toArray(),
        ];

        $response = $this->actingAs($this->user)->put('/users/roles/'.$role->id, $editedRole);

        $response->assertStatus(302);
        $response->assertRedirect('/users/roles');

        $updatedRole = Role::find($role->id);

        $this->assertEquals($editedRole['name'], $updatedRole->name);
        $this->assertEquals($editedRole['is_active'], $updatedRole->is_active);

        $this->assertDatabaseCount('permission_role', 2);
        $permissionsForRole->each(function (int $permissionId) use ($updatedRole) {
            $this->assertDatabaseHas('permission_role', [
                'permission_id' => $permissionId,
                'role_id' => $updatedRole->id,
            ]);
        });
    }

    private function createUser(): User
    {
        return User::factory()->create([
            'address_id' => Address::factory(1)->create()->first()->id,
            'role_id' => RoleEnum::boss,
        ]);
    }
}
