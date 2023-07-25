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
use App\Models\Todo;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TodoTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    private Task $task;

    public function setUp(): void
    {
        parent::setUp();

        $this->seed();

        $this->user = $this->createUser();
        $this->task = $this->createTask();
    }

    public function test_user_can_see_todos_list_in_task_show(): void
    {
        $todos = Todo::factory(3)->create([
            'task_id' => $this->task->id,
            'is_finished' => false,
        ]);

        $response = $this->actingAs($this->user)->get('tasks/'.$this->task->id);

        $response->assertStatus(200);
        $response->assertSeeText($this->task->name);
        $response->assertSeeText('Todo List');

        $this->assertDatabaseCount('todos', $todos->count());
    }

    public function test_user_can_get_to_create_todo_page(): void
    {
        $response = $this->actingAs($this->user)->get('tasks/'.$this->task->id.'/todos/create');

        $response->assertStatus(200);
        $response->assertSee('Create Todo');
    }

    public function test_user_can_store_todo(): void
    {
        $todo = [
            'name' => 'Todo',
            'dued_at' => '2023-07-01',
            'is_finished' => 0,
            'description' => 'Aenean sed pulvinar velit. Quisque suscipit, leo sit amet facilisis malesuada, neque eros lacinia sapien, eu gravida risus tellus ut enim.',
        ];

        $response = $this->actingAs($this->user)->post('tasks/'.$this->task->id.'/todos', $todo);

        $lastTodo = Todo::latest()->first();

        $response->assertStatus(302);
        $response->assertRedirect('tasks/'.$this->task->id);

        $this->assertDatabaseHas('todos', [
            'id' => $lastTodo->id,
        ]);

        $this->assertEquals($todo['name'], $lastTodo->name);
        $this->assertEquals($todo['dued_at'], $lastTodo->dued_at->format('Y-m-d'));
        $this->assertEquals($todo['description'], $lastTodo->description);
    }

    public function test_user_can_get_to_edit_todo_page(): void
    {
        $todo = Todo::factory()->create([
            'task_id' => $this->task->id,
            'is_finished' => false,
        ]);

        $response = $this->actingAs($this->user)->get('tasks/'.$this->task->id.'/todos/'.$todo->id.'/edit');

        $response->assertStatus(200);
        $response->assertSee('Edit Todo');
        $response->assertSee('value="'.$todo->name.'"', false);
        $response->assertSee('value="'.$todo->dued_at->format('Y-m-d').'"', false);
        $response->assertSee('>'.$todo->description.'</textarea>', false);
        $response->assertViewHas('todo', $todo);
    }

    public function test_user_can_update_todo(): void
    {
        $todo = Todo::factory()->create([
            'task_id' => $this->task->id,
            'is_finished' => false,
        ]);
        $editedTodo = [
            'name' => 'Updated Todo',
            'dued_at' => '2023-07-01',
            'is_finished' => 0,
            'description' => 'Aenean sed pulvinar velit. Quisque suscipit, leo sit amet facilisis malesuada, neque eros lacinia sapien, eu gravida risus tellus ut enim.',
        ];

        $response = $this->actingAs($this->user)->put('tasks/'.$this->task->id.'/todos/'.$todo->id, $editedTodo);

        $response->assertStatus(302);
        $response->assertRedirect('tasks/'.$this->task->id);

        $updatedTodo = Todo::find($todo->id);

        $this->assertEquals($editedTodo['name'], $updatedTodo->name);
        $this->assertEquals($editedTodo['dued_at'], $updatedTodo->dued_at->format('Y-m-d'));
        $this->assertEquals($editedTodo['description'], $updatedTodo->description);
    }

    public function test_user_can_check_todo(): void
    {
        $todo = Todo::factory()->create([
            'task_id' => $this->task->id,
            'is_finished' => false,
        ]);

        // Check Todo
        $response = $this->actingAs($this->user)->patch('tasks/'.$this->task->id.'/todos/'.$todo->id.'/check');

        $response->assertStatus(200);

        $response->assertJsonPath('message', __('messages.todo.finish'));
        $response->assertJsonPath('todo.id', $todo->id);

        $markedTodo = Todo::find($todo->id);
        $this->assertEquals($markedTodo->is_finished, true);

        // Unmark Todo
        $response = $this->actingAs($this->user)->patch('tasks/'.$this->task->id.'/todos/'.$todo->id.'/check');

        $response->assertStatus(200);

        $response->assertJsonPath('message', __('messages.todo.return'));
        $response->assertJsonPath('todo.id', $todo->id);

        $markedTodo = Todo::find($todo->id);
        $this->assertEquals($markedTodo->is_marked, false);
    }

    public function test_user_can_delete_todo(): void
    {
        $todo = Todo::factory()->create([
            'task_id' => $this->task->id,
            'is_finished' => false,
        ]);

        $response = $this->actingAs($this->user)->delete('tasks/'.$this->task->id.'/todos/'.$todo->id);

        $response->assertStatus(200);
        $response->assertJsonPath('message', __('messages.todo.delete'));

        $this->assertSoftDeleted($todo);
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
}
