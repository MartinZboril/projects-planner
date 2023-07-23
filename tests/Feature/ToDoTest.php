<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Task;
use App\Models\User;
use App\Models\Client;
use App\Enums\RoleEnum;
use App\Models\Address;
use App\Models\Project;
use App\Models\SocialNetwork;
use App\Enums\ProjectStatusEnum;
use App\Models\ToDo;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ToDoTest extends TestCase
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
        $todos = ToDo::factory(3)->create([
            'task_id' => $this->task->id,
            'is_finished' => false,
        ]);

        $response = $this->actingAs($this->user)->get('tasks/'.$this->task->id);

        $response->assertStatus(200);
        $response->assertSeeText($this->task->name);
        $response->assertSeeText('ToDo List');

        $this->assertDatabaseCount('todos', $todos->count());
    }

    public function test_user_can_get_to_create_todo_page(): void
    {
        $response = $this->actingAs($this->user)->get('tasks/'.$this->task->id.'/todos/create');

        $response->assertStatus(200);
        $response->assertSee('Create ToDo');
    }

    public function test_user_can_store_todo(): void
    {
        $todo = [
            'name' => 'ToDo',
            'dued_at' => '2023-07-01',
            'is_finished' => 0,
            'description' => 'Aenean sed pulvinar velit. Quisque suscipit, leo sit amet facilisis malesuada, neque eros lacinia sapien, eu gravida risus tellus ut enim.',
        ];

        $response = $this->actingAs($this->user)->post('tasks/'.$this->task->id.'/todos', $todo);

        $lastToDo = ToDo::latest()->first();

        $response->assertStatus(302);
        $response->assertRedirect('tasks/'.$this->task->id);

        $this->assertDatabaseHas('todos', [
            'id' => $lastToDo->id,
        ]);

        $this->assertEquals($todo['name'], $lastToDo->name);
        $this->assertEquals($todo['dued_at'], $lastToDo->dued_at->format('Y-m-d'));
        $this->assertEquals($todo['description'], $lastToDo->description);
    }

    public function test_user_can_get_to_edit_todo_page(): void
    {
        $todo = ToDo::factory()->create([
            'task_id' => $this->task->id,
            'is_finished' => false,
        ]);

        $response = $this->actingAs($this->user)->get('tasks/'.$this->task->id.'/todos/'.$todo->id.'/edit');

        $response->assertStatus(200);
        $response->assertSee('Edit ToDo');
        $response->assertSee('value="'.$todo->name.'"', false);
        $response->assertSee('value="'.$todo->dued_at->format('Y-m-d').'"', false);
        $response->assertSee('>'.$todo->description.'</textarea>', false);
        $response->assertViewHas('todo', $todo);
    }

    public function test_user_can_update_todo(): void
    {
        $todo = ToDo::factory()->create([
            'task_id' => $this->task->id,
            'is_finished' => false,
        ]);
        $editedTodo = [
            'name' => 'Updated ToDo',
            'dued_at' => '2023-07-01',
            'is_finished' => 0,
            'description' => 'Aenean sed pulvinar velit. Quisque suscipit, leo sit amet facilisis malesuada, neque eros lacinia sapien, eu gravida risus tellus ut enim.',
        ];

        $response = $this->actingAs($this->user)->put('tasks/'.$this->task->id.'/todos/'.$todo->id, $editedTodo);

        $response->assertStatus(302);
        $response->assertRedirect('tasks/'.$this->task->id);

        $updatedToDo = ToDo::find($todo->id);

        $this->assertEquals($editedTodo['name'], $updatedToDo->name);
        $this->assertEquals($editedTodo['dued_at'], $updatedToDo->dued_at->format('Y-m-d'));
        $this->assertEquals($editedTodo['description'], $updatedToDo->description);
    }


    public function test_user_can_check_todo(): void
    {
        $todo = ToDo::factory()->create([
            'task_id' => $this->task->id,
            'is_finished' => false,
        ]);

        // Check ToDo
        $response = $this->actingAs($this->user)->patch('tasks/'.$this->task->id.'/todos/'.$todo->id.'/check');

        $response->assertStatus(200);

        $response->assertJsonPath('message', __('messages.todo.finish'));
        $response->assertJsonPath('todo.id', $todo->id);

        $markedToDo = ToDo::find($todo->id);
        $this->assertEquals($markedToDo->is_finished, true);

        // Unmark ToDo
        $response = $this->actingAs($this->user)->patch('tasks/'.$this->task->id.'/todos/'.$todo->id.'/check');

        $response->assertStatus(200);

        $response->assertJsonPath('message', __('messages.todo.return'));
        $response->assertJsonPath('todo.id', $todo->id);

        $markedToDo = ToDo::find($todo->id);
        $this->assertEquals($markedToDo->is_marked, false);
    }

    public function test_user_can_delete_todo(): void
    {
        $todo = ToDo::factory()->create([
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
        ]);
    }
}
