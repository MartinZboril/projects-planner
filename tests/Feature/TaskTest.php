<?php

namespace Tests\Feature;

use App\Enums\ProjectStatusEnum;
use App\Enums\RoleEnum;
use App\Enums\TaskStatusEnum;
use App\Events\Task\Status\TaskCompleted;
use App\Events\Task\Status\TaskInProgressed;
use App\Events\Task\Status\TaskPaused;
use App\Events\Task\Status\TaskResumed;
use App\Events\Task\Status\TaskReturned;
use App\Events\Task\TaskMilestoneChanged;
use App\Events\Task\TaskUserChanged;
use App\Models\Address;
use App\Models\Client;
use App\Models\Comment;
use App\Models\File;
use App\Models\Project;
use App\Models\SocialNetwork;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Event;
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
        Event::fake();

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

        Event::assertDispatched(TaskUserChanged::class);

        if ($lastTask->milestone->id ?? false) {
            Event::assertDispatched(TaskMilestoneChanged::class);
        }
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
        Event::fake();

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

        if ($task->user->id !== $updatedTask->user->id) {
            Event::assertDispatched(TaskUserChanged::class);
        }

        if ($task->milestone->id ?? false && $task->milestone->id !== $updatedTask->milestone->id) {
            Event::assertDispatched(TaskMilestoneChanged::class);
        }
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
        Event::fake();

        $task = $this->createTask();

        $this->assertEquals(TaskStatusEnum::new->value, $task->status->value);

        Event::assertNotDispatched(TaskReturned::class);

        // In Progressed
        $response = $this->actingAs($this->user)->patch('tasks/'.$task->id.'/change-status', [
            'status' => TaskStatusEnum::in_progress->value,
        ]);

        $response->assertStatus(200);

        $response->assertJsonPath('message', __('messages.task.'.TaskStatusEnum::in_progress->name));
        $response->assertJsonPath('task.id', $task->id);
        $response->assertJsonPath('task.status', TaskStatusEnum::in_progress->value);

        Event::assertDispatched(TaskInProgressed::class);

        // Pause
        $response = $this->actingAs($this->user)->patch('tasks/'.$task->id.'/pause');

        $response->assertStatus(200);

        $response->assertJsonPath('message', __('messages.task.stop'));
        $response->assertJsonPath('task.id', $task->id);
        $response->assertJsonPath('task.is_stopped', 1);
        $response->assertJsonPath('task.status', TaskStatusEnum::in_progress->value);

        Event::assertDispatched(TaskPaused::class);

        // Resumed
        $response = $this->actingAs($this->user)->patch('tasks/'.$task->id.'/pause');

        $response->assertStatus(200);

        $response->assertJsonPath('message', __('messages.task.resume'));
        $response->assertJsonPath('task.id', $task->id);
        $response->assertJsonPath('task.is_stopped', 0);
        $response->assertJsonPath('task.status', TaskStatusEnum::in_progress->value);

        Event::assertDispatched(TaskResumed::class);

        // Complete
        $response = $this->actingAs($this->user)->patch('tasks/'.$task->id.'/change-status', [
            'status' => TaskStatusEnum::complete->value,
        ]);

        $response->assertStatus(200);

        $response->assertJsonPath('message', __('messages.task.'.TaskStatusEnum::complete->name));
        $response->assertJsonPath('task.id', $task->id);
        $response->assertJsonPath('task.status', TaskStatusEnum::complete->value);

        Event::assertDispatched(TaskCompleted::class);

        // Return
        $response = $this->actingAs($this->user)->patch('tasks/'.$task->id.'/change-status', [
            'status' => TaskStatusEnum::new->value,
        ]);

        $response->assertStatus(200);

        $response->assertJsonPath('message', __('messages.task.'.TaskStatusEnum::new->name));
        $response->assertJsonPath('task.id', $task->id);
        $response->assertJsonPath('task.is_returned', 1);
        $response->assertJsonPath('task.status', TaskStatusEnum::new->value);

        Event::assertDispatched(TaskReturned::class);
    }

    public function test_user_can_delete_task(): void
    {
        $task = $this->createTask();

        $response = $this->actingAs($this->user)->delete('tasks/'.$task->id);

        $response->assertStatus(200);
        $response->assertJsonPath('message', __('messages.task.delete'));

        $this->assertSoftDeleted($task);
    }

    public function test_user_can_upload_file_for_task(): void
    {
        $task = $this->createTask();

        $taskFileName = 'task.jpg';

        $taskFiles = [
            'fileable_id' => $task->id,
            'fileable_type' => $task::class,
            'files' => [
                UploadedFile::fake()->image($taskFileName),
            ],
        ];

        $response = $this->actingAs($this->user)->post('tasks/'.$task->id.'/files/upload', $taskFiles);

        $response->assertStatus(302);
        $response->assertRedirect('tasks/'.$task->id);

        $lastTaskFile = File::where('fileable_id', $task->id)->where('fileable_type', $task::class)->latest()->first();
        $this->assertEquals($taskFiles['fileable_id'], $lastTaskFile->fileable_id);
        $this->assertEquals($taskFiles['fileable_type'], $lastTaskFile->fileable_type);
        $this->assertEquals($taskFileName, $lastTaskFile->file_name);
        $this->assertEquals('tasks/files', $lastTaskFile->collection);
    }

    public function test_user_can_delete_file_for_task(): void
    {
        $task = $this->createTask();

        $taskFile = File::factory()->create([
            'fileable_id' => $task->id,
            'fileable_type' => $task::class,
        ]);

        $response = $this->actingAs($this->user)->delete('tasks/'.$task->id.'/files/'.$taskFile->id);

        $response->assertStatus(200);
        $response->assertJsonPath('message', __('messages.file.delete'));

        $this->assertSoftDeleted($taskFile);
    }

    public function test_user_can_store_tasks_comment(): void
    {
        $task = $this->createTask();

        $taskComment = [
            'commentable_id' => $task->id,
            'commentable_type' => $task::class,
            'user_id' => $this->user->id,
            'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
        ];

        $response = $this->actingAs($this->user)->post('tasks/'.$task->id.'/comments', $taskComment);

        $response->assertStatus(302);
        $response->assertRedirect('tasks/'.$task->id);

        $this->assertDatabaseHas('comments', $taskComment);

        $lastTask = Comment::where('commentable_id', $task->id)->where('commentable_type', $task::class)->latest()->first();
        $this->assertEquals($taskComment['commentable_id'], $lastTask->commentable_id);
        $this->assertEquals($taskComment['commentable_type'], $lastTask->commentable_type);
        $this->assertEquals($taskComment['content'], $lastTask->content);
    }

    public function test_user_can_update_tasks_comment(): void
    {
        $task = $this->createTask();

        $taskComment = Comment::factory()->create([
            'user_id' => $this->user->id,
            'commentable_id' => $task->id,
            'commentable_type' => $task::class,
        ]);

        $editedTaskComment = [
            'content' => 'Task Comment',
        ];

        $response = $this->actingAs($this->user)->put('tasks/'.$task->id.'/comments/'.$taskComment->id, $editedTaskComment);

        $response->assertStatus(302);
        $response->assertRedirect('tasks/'.$task->id);

        $lastTaskComment = Comment::where('commentable_id', $task->id)->where('commentable_type', $task::class)->latest()->first();
        $this->assertEquals($editedTaskComment['content'], $lastTaskComment->content);
    }

    public function test_user_can_delete_tasks_comment(): void
    {
        $task = $this->createTask();

        $taskComment = Comment::factory()->create([
            'user_id' => $this->user->id,
            'commentable_id' => $task->id,
            'commentable_type' => $task::class,
        ]);

        $response = $this->actingAs($this->user)->delete('tasks/'.$task->id.'/comments/'.$taskComment->id);

        $response->assertStatus(200);
        $response->assertJsonPath('message', __('messages.comment.delete'));

        $this->assertSoftDeleted($taskComment);
    }

    public function test_user_can_upload_files_to_tasks_comment(): void
    {
        $task = $this->createTask();

        $taskComment = Comment::factory()->create([
            'user_id' => $this->user->id,
            'commentable_id' => $task->id,
            'commentable_type' => $task::class,
        ]);

        [$taskCommentFile1, $taskCommentFile2] = ['task_comment_1.jpg', 'task_comment_2.jpg'];

        $editedTaskComment = [
            'content' => 'Updated comments with files',
            'files' => [
                UploadedFile::fake()->image($taskCommentFile1),
                UploadedFile::fake()->image($taskCommentFile2),
            ],
        ];

        $response = $this->actingAs($this->user)->put('tasks/'.$task->id.'/comments/'.$taskComment->id, $editedTaskComment);

        $response->assertStatus(302);
        $response->assertRedirect('tasks/'.$task->id);

        $lastTaskComment = Comment::where('commentable_id', $task->id)->where('commentable_type', $task::class)->latest()->first();
        $lastTaskCommentFiles = File::where('fileable_id', $lastTaskComment->id)->where('fileable_type', $lastTaskComment::class)->latest()->get();
        $this->assertEquals(2, $lastTaskCommentFiles->count());
        $this->assertEquals($taskCommentFile1, $lastTaskCommentFiles[0]->file_name);
        $this->assertEquals('comments', $lastTaskCommentFiles[0]->collection);
        $this->assertEquals($taskCommentFile2, $lastTaskCommentFiles[1]->file_name);
        $this->assertEquals('comments', $lastTaskCommentFiles[1]->collection);
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
