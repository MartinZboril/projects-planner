<?php

namespace Tests\Feature;

use App\Enums\RoleEnum;
use App\Models\Address;
use App\Models\File;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    public function setUp(): void
    {
        parent::setUp();

        $this->seed();

        $this->user = $this->createUser();
    }

    public function test_user_can_see_users_index(): void
    {
        $response = $this->actingAs($this->user)->get('/users');

        $response->assertStatus(200);
        $response->assertSee('Users');

        $this->assertDatabaseCount('users', 1);
    }

    public function test_user_can_see_another_users_show(): void
    {
        $user = $this->createUser();

        $response = $this->actingAs($this->user)->get('users/'.$user->id);

        $response->assertStatus(200);
        $response->assertSeeText($user->name);
    }

    public function test_user_can_get_to_create_another_user_page(): void
    {
        $response = $this->actingAs($this->user)->get('users/create');

        $response->assertStatus(200);
        $response->assertSee('Create User');
    }

    public function test_user_can_store_another_user(): void
    {
        $user = $this->getUserArray();

        $response = $this->actingAs($this->user)->post('users', $user);

        $createdUser = User::where('email', $user['email'])->first();

        $response->assertStatus(302);
        $response->assertRedirect('users/'.$createdUser->id);

        $this->assertDatabaseHas('users', [
            'id' => $createdUser->id,
        ]);

        $this->assertEquals($user['name'], $createdUser->name);
        $this->assertEquals($user['surname'], $createdUser->surname);
        $this->assertEquals($user['email'], $createdUser->email);
        $this->assertEquals($user['job_title'], $createdUser->job_title);
        $this->assertEquals($user['country'], $createdUser->address->country);
    }

    public function test_user_can_get_to_edit_another_user_page(): void
    {
        $user = $this->createUser();

        $response = $this->actingAs($this->user)->get('users/'.$user->id.'/edit');

        $response->assertStatus(200);
        $response->assertSee('Edit User');
        $response->assertSee('value="'.$user->name.'"', false);
        $response->assertSee('value="'.$user->address->street.'"', false);
        $response->assertViewHas('user', $user);
    }

    public function test_user_can_update_another_user(): void
    {
        $user = $this->createUser();
        $editedUser = $this->getUserArray();

        $response = $this->actingAs($this->user)->put('users/'.$user->id, $editedUser);

        $response->assertStatus(302);
        $response->assertRedirect('users/'.$user->id);

        $updatedUser = User::find($user->id);

        $this->assertEquals($editedUser['name'], $updatedUser->name);
        $this->assertEquals($editedUser['surname'], $updatedUser->surname);
        $this->assertEquals($editedUser['email'], $updatedUser->email);
        $this->assertEquals($editedUser['job_title'], $updatedUser->job_title);
        $this->assertEquals($editedUser['country'], $updatedUser->address->country);
    }

    public function test_user_can_work_with_another_user_logo(): void
    {
        $user = $this->createUser();
        $editedUser = $this->getUserArray();

        $userFileName = 'user.jpg';
        $editedUser['avatar'] = UploadedFile::fake()->image($userFileName);

        // Upload
        $response = $this->actingAs($this->user)->put('users/'.$user->id, $editedUser);

        $response->assertStatus(302);
        $response->assertRedirect('users/'.$user->id);

        $lastAvatarFile = File::latest()->first();
        $this->assertEquals($userFileName, $lastAvatarFile->file_name);
        $this->assertEquals('users/avatars', $lastAvatarFile->collection);

        // Remove
        $response = $this->actingAs($this->user)->delete('users/'.$user->id.'/avatar/remove');

        $response->assertStatus(200);
        $response->assertJsonPath('message', __('messages.user.avatar.delete'));

        $this->assertEquals($user->avatar_id, null);
    }

    public function test_user_can_delete_another_user(): void
    {
        $user = $this->createUser();

        $response = $this->actingAs($this->user)->delete('users/'.$user->id);

        $response->assertStatus(200);
        $response->assertJsonPath('message', __('messages.user.delete'));

        $this->assertSoftDeleted($user);
    }

    private function createUser(): User
    {
        return User::factory()->create([
            'address_id' => Address::factory(1)->create()->first()->id,
            'role_id' => RoleEnum::boss,
        ]);
    }

    private function getUserArray(): array
    {
        return [
            'role_id' => RoleEnum::boss->value,
            'name' => 'User',
            'surname' => 'Test',
            'email' => 'user@test.com',
            'username' => 'Tester',
            'password' => 'password',
            'job_title' => 'Test',
            'mobile' => '(888) 937-7238',
            'phone' => '201-886-0269 x3767',
            'street' => 'Keegan Trail',
            'house_number' => '484',
            'city' => 'West Judge',
            'country' => 'Falkland Islands',
            'zip_code' => '2001',
        ];
    }
}
