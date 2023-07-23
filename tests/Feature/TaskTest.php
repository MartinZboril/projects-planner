<?php

namespace Tests\Feature;

use App\Enums\ProjectStatusEnum;
use App\Enums\RoleEnum;
use App\Enums\TaskStatusEnum;
use App\Models\Address;
use App\Models\Client;
use App\Models\Project;
use App\Models\SocialNetwork;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    public function setUp(): void
    {
        parent::setUp();

        $this->seed();

        $this->user = $this->createUser();
    }

    public function test_user_can_see_tasks_index(): void
    {
        $response = $this->actingAs($this->user)->get('tasks');

        $response->assertStatus(200);
        $response->assertSee('Tasks');

        $this->assertDatabaseCount('tasks', 0);
    }

    public function test_user_can_see_tasks_show(): void
    {
        $this->actingAs($this->user);

        $task = $this->createTask();

        // Show
        $response = $this->actingAs($this->user)->get('tasks/'.$task->id);
        $response->assertStatus(200);
        $response->assertSeeText($task->name);
    }

    public function test_user_can_get_to_create_task_page(): void
    {
        $response = $this->actingAs($this->user)->get('tasks/create');

        $response->assertStatus(200);
        $response->assertSee('Create Task');
    }

    public function test_user_can_store_task(): void
    {
        $task = $this->getTaskArray();

        $response = $this->actingAs($this->user)->post('tasks', $task);

        $lastTask = Task::latest()->first();

        $response->assertStatus(302);
        $response->assertRedirect('tasks/'.$lastTask->id);

        $this->assertDatabaseHas('tasks', [
            'id' => $lastTask->id,
        ]);

        $this->assertEquals($task['name'], $lastTask->name);
        $this->assertEquals($task['dued_at'], $lastTask->dued_at->format('Y-m-d'));
        $this->assertEquals($task['description'], $lastTask->description);
        $this->assertEquals($task['project_id'], $lastTask->project->id);
        $this->assertEquals($task['user_id'], $lastTask->user->id);
        $this->assertEquals(TaskStatusEnum::new, $lastTask->status);
    }

    public function test_user_can_get_to_edit_task_page(): void
    {
        $task = $this->createTask();

        $response = $this->actingAs($this->user)->get('tasks/'.$task->id.'/edit');

        $response->assertStatus(200);
        $response->assertSee('Edit Task');
        $response->assertSee('value="'.$task->name.'"', false);
        $response->assertSee('value="'.$task->started_at->format('Y-m-d').'"', false);
        $response->assertSee('>'.$task->description.'</textarea>', false);
        $response->assertViewHas('task', $task);
    }

    public function test_user_can_update_task(): void
    {
        $task = $this->createTask();
        $editedTask = $this->getTaskArray();

        $response = $this->actingAs($this->user)->put('tasks/'.$task->id, $editedTask);

        $response->assertStatus(302);
        $response->assertRedirect('tasks/'.$task->id);

        $updatedTask = Task::find($task->id);

        $this->assertEquals($editedTask['name'], $updatedTask->name);
        $this->assertEquals($editedTask['description'], $updatedTask->description);
        $this->assertEquals($editedTask['project_id'], $updatedTask->project->id);
        $this->assertEquals($editedTask['user_id'], $updatedTask->user->id);
    }

    public function test_user_can_mark_task(): void
    {
        $task = $this->createTask();

        // Mark task
        $response = $this->actingAs($this->user)->patch('tasks/'.$task->id.'/mark');

        $response->assertStatus(200);

        $response->assertJsonPath('message', __('messages.task.mark'));
        $response->assertJsonPath('task.id', $task->id);

        $markedTask = Task::find($task->id);
        $this->assertEquals($markedTask->is_marked, true);

        // Unmark task
        $response = $this->actingAs($this->user)->patch('tasks/'.$task->id.'/mark');

        $response->assertStatus(200);

        $response->assertJsonPath('message', __('messages.task.unmark'));
        $response->assertJsonPath('task.id', $task->id);

        $markedTask = Task::find($task->id);
        $this->assertEquals($markedTask->is_marked, false);
    }

    public function test_user_can_change_task_status(): void
    {
        $task = $this->createTask();

        $this->assertEquals(TaskStatusEnum::new->value, $task->status->value);

        // In Progressed
        $response = $this->actingAs($this->user)->patch('tasks/'.$task->id.'/change-status', [
            'status' => TaskStatusEnum::in_progress->value,
        ]);

        $response->assertStatus(200);

        $response->assertJsonPath('message', __('messages.task.'.TaskStatusEnum::in_progress->name));
        $response->assertJsonPath('task.id', $task->id);
        $response->assertJsonPath('task.status', TaskStatusEnum::in_progress->value);

        // Pause
        $response = $this->actingAs($this->user)->patch('tasks/'.$task->id.'/pause');

        $response->assertStatus(200);

        $response->assertJsonPath('message', __('messages.task.stop'));
        $response->assertJsonPath('task.id', $task->id);
        $response->assertJsonPath('task.is_stopped', 1);
        $response->assertJsonPath('task.status', TaskStatusEnum::in_progress->value);

        // Resumed
        $response = $this->actingAs($this->user)->patch('tasks/'.$task->id.'/pause');

        $response->assertStatus(200);

        $response->assertJsonPath('message', __('messages.task.resume'));
        $response->assertJsonPath('task.id', $task->id);
        $response->assertJsonPath('task.is_stopped', 0);
        $response->assertJsonPath('task.status', TaskStatusEnum::in_progress->value);

        // Complete
        $response = $this->actingAs($this->user)->patch('tasks/'.$task->id.'/change-status', [
            'status' => TaskStatusEnum::complete->value,
        ]);

        $response->assertStatus(200);

        $response->assertJsonPath('message', __('messages.task.'.TaskStatusEnum::complete->name));
        $response->assertJsonPath('task.id', $task->id);
        $response->assertJsonPath('task.status', TaskStatusEnum::complete->value);

        // Return
        $response = $this->actingAs($this->user)->patch('tasks/'.$task->id.'/change-status', [
            'status' => TaskStatusEnum::new->value,
        ]);

        $response->assertStatus(200);

        $response->assertJsonPath('message', __('messages.task.'.TaskStatusEnum::new->name));
        $response->assertJsonPath('task.id', $task->id);
        $response->assertJsonPath('task.is_returned', 1);
        $response->assertJsonPath('task.status', TaskStatusEnum::new->value);
    }

    public function test_user_can_delete_task(): void
    {
        $task = $this->createTask();

        $response = $this->actingAs($this->user)->delete('tasks/'.$task->id);

        $response->assertStatus(200);
        $response->assertJsonPath('message', __('messages.task.delete'));

        $this->assertSoftDeleted($task);
    }

    private function createUser(): User
    {
        return User::factory()->create([
            'address_id' => Address::factory(1)->create()->first()->id,
            'role_id' => RoleEnum::boss,
        ]);
    }

    private function createTask(): Task
    {
        $this->actingAs($this->user);

        $project = Project::factory()->create([
            'client_id' => Client::factory()->create([
                'address_id' => Address::factory()->create()->first()->id,
                'social_network_id' => SocialNetwork::factory()->create()->first()->id,
            ])->id,
            'status' => ProjectStatusEnum::active->value,
        ]);

        [$authorId, $userId] = User::factory(2)->create([
            'address_id' => Address::factory(1)->create()->first()->id,
            'role_id' => RoleEnum::employee,
        ])->pluck('id');
        $project->team()->attach([$authorId, $userId]);

        return Task::factory()->create([
            'project_id' => $project->id,
            'author_id' => $authorId,
            'user_id' => $userId,
            'status' => TaskStatusEnum::new->value,
        ]);
    }

    private function getTaskArray(): array
    {
        $project = Project::factory()->create([
            'client_id' => Client::factory()->create([
                'address_id' => Address::factory()->create()->first()->id,
                'social_network_id' => SocialNetwork::factory()->create()->first()->id,
            ])->id,
            'status' => ProjectStatusEnum::active->value,
        ]);

        [$authorId, $userId] = User::factory(2)->create([
            'address_id' => Address::factory(1)->create()->first()->id,
            'role_id' => RoleEnum::employee,
        ])->pluck('id');
        $project->team()->attach([$authorId, $userId]);

        return [
            'project_id' => $project->id,
            'author_id' => $authorId,
            'user_id' => $userId,
            'status' => TaskStatusEnum::new->value,
            'name' => 'Task',
            'started_at' => '2023-07-08',
            'dued_at' => '2023-07-10',
            'description' => 'Aenean sed pulvinar velit. Quisque suscipit, leo sit amet facilisis malesuada, neque eros lacinia sapien, eu gravida risus tellus ut enim.',
        ];
    }
}
