<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\File;
use App\Models\Task;
use App\Models\User;
use App\Models\Client;
use App\Enums\RoleEnum;
use App\Models\Address;
use App\Models\Comment;
use App\Models\Project;
use App\Enums\TaskStatusEnum;
use App\Models\SocialNetwork;
use App\Enums\ProjectStatusEnum;
use Illuminate\Http\UploadedFile;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CommentTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    public function setUp(): void
    {
        parent::setUp();

        $this->seed();

        $this->user = $this->createUser();
    }

    public function test_user_can_store_comment_for_different_models(): void
    {
        $comment = [
            'user_id' => $this->user->id,
            'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
        ];

        // Client
        $client = Client::factory()->create([
            'address_id' => Address::factory()->create()->first()->id,
            'social_network_id' => SocialNetwork::factory()->create()->first()->id,
        ]);

        $clientComment = $comment + [
            'commentable_id' => $client->id,
            'commentable_type' => $client::class,
        ];

        $response = $this->actingAs($this->user)->post('clients/'.$client->id.'/comments', $clientComment);

        $response->assertStatus(302);
        $response->assertRedirect('clients/'.$client->id.'/comments');

        $this->assertDatabaseHas('comments', $clientComment);

        $lastComment = Comment::where('commentable_id', $client->id)->where('commentable_type', $client::class)->latest()->first();
        $this->assertEquals($clientComment['commentable_id'], $lastComment->commentable_id);
        $this->assertEquals($clientComment['commentable_type'], $lastComment->commentable_type);
        $this->assertEquals($clientComment['content'], $lastComment->content);

        $comment = [
            'user_id' => $this->user->id,
            'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
        ];

        // Project
        $project = Project::factory()->create([
            'client_id' => Client::factory()->create([
                'address_id' => Address::factory()->create()->first()->id,
                'social_network_id' => SocialNetwork::factory()->create()->first()->id,
            ])->id,
            'status' => ProjectStatusEnum::active->value,
        ]);
        $project->team()->attach(User::factory(2)->create([
            'address_id' => Address::factory(1)->create()->first()->id,
            'role_id' => RoleEnum::employee,
        ])->pluck('id'));

        $projectComment = $comment + [
            'commentable_id' => $project->id,
            'commentable_type' => $project::class,
        ];

        $response = $this->actingAs($this->user)->post('projects/'.$project->id.'/comments', $projectComment);

        $response->assertStatus(302);
        $response->assertRedirect('projects/'.$project->id.'/comments');

        $this->assertDatabaseHas('comments', $projectComment);

        $lastProject = Comment::where('commentable_id', $project->id)->where('commentable_type', $project::class)->latest()->first();
        $this->assertEquals($projectComment['commentable_id'], $lastProject->commentable_id);
        $this->assertEquals($projectComment['commentable_type'], $lastProject->commentable_type);
        $this->assertEquals($projectComment['content'], $lastProject->content);

        // Task
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

        $task = Task::factory()->create([
            'project_id' => $project->id,
            'author_id' => $authorId,
            'user_id' => $userId,
            'status' => TaskStatusEnum::new->value,
        ]);

        $taskComment = $comment + [
            'commentable_id' => $task->id,
            'commentable_type' => $task::class,
        ];

        $response = $this->actingAs($this->user)->post('tasks/'.$task->id.'/comments', $taskComment);

        $response->assertStatus(302);
        $response->assertRedirect('tasks/'.$task->id);

        $this->assertDatabaseHas('comments', $taskComment);

        $lastTask = Comment::where('commentable_id', $task->id)->where('commentable_type', $task::class)->latest()->first();
        $this->assertEquals($taskComment['commentable_id'], $lastTask->commentable_id);
        $this->assertEquals($taskComment['commentable_type'], $lastTask->commentable_type);
        $this->assertEquals($taskComment['content'], $lastTask->content);

        // TODO:: Milestone
        // TODO:: Ticket
    }

    public function test_user_can_update_comment_for_different_models(): void
    {
        // Client
        $client = Client::factory()->create([
            'address_id' => Address::factory()->create()->first()->id,
            'social_network_id' => SocialNetwork::factory()->create()->first()->id,
        ]);

        $clientComment = Comment::factory()->create([
            'user_id' => $this->user->id,
            'commentable_id' => $client->id,
            'commentable_type' => $client::class,
        ]);

        $editedClientComment = [
            'content' => 'Client Comment',
        ];

        $response = $this->actingAs($this->user)->put('clients/'.$client->id.'/comments/'.$clientComment->id, $editedClientComment);

        $response->assertStatus(302);
        $response->assertRedirect('clients/'.$client->id.'/comments');

        $lastClientComment = Comment::where('commentable_id', $client->id)->where('commentable_type', $client::class)->latest()->first();
        $this->assertEquals($editedClientComment['content'], $lastClientComment->content);

        // Project
        $project = Project::factory()->create([
            'client_id' => Client::factory()->create([
                'address_id' => Address::factory()->create()->first()->id,
                'social_network_id' => SocialNetwork::factory()->create()->first()->id,
            ])->id,
            'status' => ProjectStatusEnum::active->value,
        ]);
        $project->team()->attach(User::factory(2)->create([
            'address_id' => Address::factory(1)->create()->first()->id,
            'role_id' => RoleEnum::employee,
        ])->pluck('id'));

        $projectComment = Comment::factory()->create([
            'user_id' => $this->user->id,
            'commentable_id' => $project->id,
            'commentable_type' => $project::class,
        ]);

        $editedProjectComment = [
            'content' => 'Project Comment',
        ];

        $response = $this->actingAs($this->user)->put('projects/'.$project->id.'/comments/'.$projectComment->id, $editedProjectComment);

        $response->assertStatus(302);
        $response->assertRedirect('projects/'.$project->id.'/comments');

        $lastProjectComment = Comment::where('commentable_id', $project->id)->where('commentable_type', $project::class)->latest()->first();
        $this->assertEquals($editedProjectComment['content'], $lastProjectComment->content);

        // Task
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

        $task = Task::factory()->create([
            'project_id' => $project->id,
            'author_id' => $authorId,
            'user_id' => $userId,
            'status' => TaskStatusEnum::new->value,
        ]);

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


        // TODO:: Milestone
        // TODO:: Ticket
    }

    public function test_user_can_delete_comment_for_different_models(): void
    {
        // Client
        $client = Client::factory()->create([
            'address_id' => Address::factory()->create()->first()->id,
            'social_network_id' => SocialNetwork::factory()->create()->first()->id,
        ]);

        $clientComment = Comment::factory()->create([
            'user_id' => $this->user->id,
            'commentable_id' => $client->id,
            'commentable_type' => $client::class,
        ]);

        $response = $this->actingAs($this->user)->delete('clients/'.$client->id.'/comments/'.$clientComment->id);

        $response->assertStatus(200);
        $response->assertJsonPath('message', __('messages.comment.delete'));

        $this->assertSoftDeleted($clientComment);

        // Project
        $project = Project::factory()->create([
            'client_id' => Client::factory()->create([
                'address_id' => Address::factory()->create()->first()->id,
                'social_network_id' => SocialNetwork::factory()->create()->first()->id,
            ])->id,
            'status' => ProjectStatusEnum::active->value,
        ]);
        $project->team()->attach(User::factory(2)->create([
            'address_id' => Address::factory(1)->create()->first()->id,
            'role_id' => RoleEnum::employee,
        ])->pluck('id'));

        $projectComment = Comment::factory()->create([
            'user_id' => $this->user->id,
            'commentable_id' => $project->id,
            'commentable_type' => $project::class,
        ]);

        $response = $this->actingAs($this->user)->delete('projects/'.$project->id.'/comments/'.$projectComment->id);

        $response->assertStatus(200);
        $response->assertJsonPath('message', __('messages.comment.delete'));

        $this->assertSoftDeleted($projectComment);

        // Task
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

        $task = Task::factory()->create([
            'project_id' => $project->id,
            'author_id' => $authorId,
            'user_id' => $userId,
            'status' => TaskStatusEnum::new->value,
        ]);

        $taskComment = Comment::factory()->create([
            'user_id' => $this->user->id,
            'commentable_id' => $task->id,
            'commentable_type' => $task::class,
        ]);

        $response = $this->actingAs($this->user)->delete('tasks/'.$task->id.'/comments/'.$taskComment->id);

        $response->assertStatus(200);
        $response->assertJsonPath('message', __('messages.comment.delete'));

        $this->assertSoftDeleted($taskComment);

        // TODO:: Milestone
        // TODO:: Ticket
    }

    public function test_user_can_upload_files_to_comments(): void
    {
        // Client
        $client = Client::factory()->create([
            'address_id' => Address::factory()->create()->first()->id,
            'social_network_id' => SocialNetwork::factory()->create()->first()->id,
        ]);

        $clientComment = Comment::factory()->create([
            'user_id' => $this->user->id,
            'commentable_id' => $client->id,
            'commentable_type' => $client::class,
        ]);

        [$clientCommentFile1, $clientCommentFile2] = ['client_comment_1.jpg', 'client_comment_2.jpg'];

        $editedClientComment = [
            'content' => 'Updated comments with files',
            'files' => [
                UploadedFile::fake()->image($clientCommentFile1),
                UploadedFile::fake()->image($clientCommentFile2),
            ],
        ];

        $response = $this->actingAs($this->user)->put('clients/'.$client->id.'/comments/'.$clientComment->id, $editedClientComment);

        $response->assertStatus(302);
        $response->assertRedirect('clients/'.$client->id.'/comments');

        $lastClientComment = Comment::where('commentable_id', $client->id)->where('commentable_type', $client::class)->latest()->first();
        $lastClientCommentFiles = File::where('fileable_id', $lastClientComment->id)->where('fileable_type', $lastClientComment::class)->latest()->get();
        $this->assertEquals(2, $lastClientCommentFiles->count());
        $this->assertEquals($clientCommentFile1, $lastClientCommentFiles[0]->file_name);
        $this->assertEquals('comments', $lastClientCommentFiles[0]->collection);
        $this->assertEquals($clientCommentFile2, $lastClientCommentFiles[1]->file_name);
        $this->assertEquals('comments', $lastClientCommentFiles[1]->collection);

        // Project
        $project = Project::factory()->create([
            'client_id' => Client::factory()->create([
                'address_id' => Address::factory()->create()->first()->id,
                'social_network_id' => SocialNetwork::factory()->create()->first()->id,
            ])->id,
            'status' => ProjectStatusEnum::active->value,
        ]);
        $project->team()->attach(User::factory(2)->create([
            'address_id' => Address::factory(1)->create()->first()->id,
            'role_id' => RoleEnum::employee,
        ])->pluck('id'));

        $projectComment = Comment::factory()->create([
            'user_id' => $this->user->id,
            'commentable_id' => $project->id,
            'commentable_type' => $project::class,
        ]);

        [$projectCommentFile1, $projectCommentFile2] = ['project_comment_1.jpg', 'project_comment_2.jpg'];

        $editedProjectComment = [
            'content' => 'Updated comments with files',
            'files' => [
                UploadedFile::fake()->image($projectCommentFile1),
                UploadedFile::fake()->image($projectCommentFile2),
            ],
        ];

        $response = $this->actingAs($this->user)->put('projects/'.$project->id.'/comments/'.$projectComment->id, $editedProjectComment);

        $response->assertStatus(302);
        $response->assertRedirect('projects/'.$project->id.'/comments');

        $lastProjectComment = Comment::where('commentable_id', $project->id)->where('commentable_type', $project::class)->latest()->first();
        $lastProjectCommentFiles = File::where('fileable_id', $lastProjectComment->id)->where('fileable_type', $lastProjectComment::class)->latest()->get();
        $this->assertEquals(2, $lastProjectCommentFiles->count());
        $this->assertEquals($projectCommentFile1, $lastProjectCommentFiles[0]->file_name);
        $this->assertEquals('comments', $lastProjectCommentFiles[0]->collection);
        $this->assertEquals($projectCommentFile2, $lastProjectCommentFiles[1]->file_name);
        $this->assertEquals('comments', $lastProjectCommentFiles[1]->collection);

        // Task
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

        $task = Task::factory()->create([
            'project_id' => $project->id,
            'author_id' => $authorId,
            'user_id' => $userId,
            'status' => TaskStatusEnum::new->value,
        ]);

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

        // TODO:: Milestone
        // TODO:: Ticket
    }

    private function createUser(): User
    {
        return User::factory()->create([
            'address_id' => Address::factory(1)->create()->first()->id,
            'role_id' => RoleEnum::boss,
        ]);
    }
}
