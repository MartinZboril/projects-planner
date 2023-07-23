<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\File;
use App\Models\Task;
use App\Models\User;
use App\Models\Client;
use App\Models\Ticket;
use App\Enums\RoleEnum;
use App\Models\Address;
use App\Models\Comment;
use App\Models\Project;
use App\Models\Milestone;
use App\Enums\TaskStatusEnum;
use App\Models\SocialNetwork;
use App\Enums\TicketStatusEnum;
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

        // Ticket
        $project = Project::factory()->create([
            'client_id' => Client::factory()->create([
                'address_id' => Address::factory()->create()->first()->id,
                'social_network_id' => SocialNetwork::factory()->create()->first()->id,
            ])->id,
            'status' => ProjectStatusEnum::active->value,
        ]);

        [$reporterId, $assigneeId] = User::factory(2)->create([
            'address_id' => Address::factory(1)->create()->first()->id,
            'role_id' => RoleEnum::employee,
        ])->pluck('id');
        $project->team()->attach([$reporterId, $assigneeId]);

        $ticket = Ticket::factory()->create([
            'project_id' => $project->id,
            'reporter_id' => $reporterId,
            'assignee_id' => $assigneeId,
            'status' => TicketStatusEnum::open->value,
        ]);

        $ticketComment = $comment + [
            'commentable_id' => $ticket->id,
            'commentable_type' => $ticket::class,
        ];

        $response = $this->actingAs($this->user)->post('tickets/'.$ticket->id.'/comments', $ticketComment);

        $response->assertStatus(302);
        $response->assertRedirect('tickets/'.$ticket->id);

        $this->assertDatabaseHas('comments', $ticketComment);

        $lastTicket = Comment::where('commentable_id', $ticket->id)->where('commentable_type', $ticket::class)->latest()->first();
        $this->assertEquals($ticketComment['commentable_id'], $lastTicket->commentable_id);
        $this->assertEquals($ticketComment['commentable_type'], $lastTicket->commentable_type);
        $this->assertEquals($ticketComment['content'], $lastTicket->content);

        // Milestone
        $project = Project::factory()->create([
            'client_id' => Client::factory()->create([
                'address_id' => Address::factory()->create()->first()->id,
                'social_network_id' => SocialNetwork::factory()->create()->first()->id,
            ])->id,
            'status' => ProjectStatusEnum::active->value,
        ]);

        [$ownerId] = User::factory()->create([
            'address_id' => Address::factory(1)->create()->first()->id,
            'role_id' => RoleEnum::employee,
        ])->pluck('id');
        $project->team()->attach([$ownerId]);

        $milestone = Milestone::factory()->create([
            'project_id' => $project->id,
            'owner_id' => $ownerId,
        ]);

        $milestoneComment = $comment + [
            'commentable_id' => $milestone->id,
            'commentable_type' => $milestone::class,
        ];

        $response = $this->actingAs($this->user)->post('projects/'.$milestone->project->id.'/milestones/'.$milestone->id.'/comments', $milestoneComment);

        $response->assertStatus(302);
        $response->assertRedirect('projects/'.$milestone->project->id.'/milestones/'.$milestone->id);

        $this->assertDatabaseHas('comments', $milestoneComment);

        $lastMilestone = Comment::where('commentable_id', $milestone->id)->where('commentable_type', $milestone::class)->latest()->first();
        $this->assertEquals($milestoneComment['commentable_id'], $lastMilestone->commentable_id);
        $this->assertEquals($milestoneComment['commentable_type'], $lastMilestone->commentable_type);
        $this->assertEquals($milestoneComment['content'], $lastMilestone->content);
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

        // Ticket
        $project = Project::factory()->create([
            'client_id' => Client::factory()->create([
                'address_id' => Address::factory()->create()->first()->id,
                'social_network_id' => SocialNetwork::factory()->create()->first()->id,
            ])->id,
            'status' => ProjectStatusEnum::active->value,
        ]);

        [$reporterId, $assigneeId] = User::factory(2)->create([
            'address_id' => Address::factory(1)->create()->first()->id,
            'role_id' => RoleEnum::employee,
        ])->pluck('id');
        $project->team()->attach([$reporterId, $assigneeId]);

        $ticket = Ticket::factory()->create([
            'project_id' => $project->id,
            'reporter_id' => $reporterId,
            'assignee_id' => $assigneeId,
            'status' => TicketStatusEnum::open->value,
        ]);

        $ticketComment = Comment::factory()->create([
            'user_id' => $this->user->id,
            'commentable_id' => $ticket->id,
            'commentable_type' => $ticket::class,
        ]);

        $editedTicketComment = [
            'content' => 'Ticket Comment',
        ];

        $response = $this->actingAs($this->user)->put('tickets/'.$ticket->id.'/comments/'.$ticketComment->id, $editedTicketComment);

        $response->assertStatus(302);
        $response->assertRedirect('tickets/'.$ticket->id);

        $lastTicketComment = Comment::where('commentable_id', $ticket->id)->where('commentable_type', $ticket::class)->latest()->first();
        $this->assertEquals($editedTicketComment['content'], $lastTicketComment->content);

        // Milestone
        $project = Project::factory()->create([
            'client_id' => Client::factory()->create([
                'address_id' => Address::factory()->create()->first()->id,
                'social_network_id' => SocialNetwork::factory()->create()->first()->id,
            ])->id,
            'status' => ProjectStatusEnum::active->value,
        ]);

        [$ownerId] = User::factory()->create([
            'address_id' => Address::factory(1)->create()->first()->id,
            'role_id' => RoleEnum::employee,
        ])->pluck('id');
        $project->team()->attach([$ownerId]);

        $milestone = Milestone::factory()->create([
            'project_id' => $project->id,
            'owner_id' => $ownerId,
        ]);

        $milestoneComment = Comment::factory()->create([
            'user_id' => $this->user->id,
            'commentable_id' => $milestone->id,
            'commentable_type' => $milestone::class,
        ]);

        $editedMilestoneComment = [
            'content' => 'Milestone Comment',
        ];

        $response = $this->actingAs($this->user)->put('projects/'.$milestone->project->id.'/milestones/'.$milestone->id.'/comments/'.$milestoneComment->id, $editedMilestoneComment);

        $response->assertStatus(302);
        $response->assertRedirect('projects/'.$milestone->project->id.'/milestones/'.$milestone->id);

        $lastMilestoneComment = Comment::where('commentable_id', $milestone->id)->where('commentable_type', $milestone::class)->latest()->first();
        $this->assertEquals($editedMilestoneComment['content'], $lastMilestoneComment->content);
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

        // Ticket
        $project = Project::factory()->create([
            'client_id' => Client::factory()->create([
                'address_id' => Address::factory()->create()->first()->id,
                'social_network_id' => SocialNetwork::factory()->create()->first()->id,
            ])->id,
            'status' => ProjectStatusEnum::active->value,
        ]);

        [$reporterId, $assigneeId] = User::factory(2)->create([
            'address_id' => Address::factory(1)->create()->first()->id,
            'role_id' => RoleEnum::employee,
        ])->pluck('id');
        $project->team()->attach([$reporterId, $assigneeId]);

        $ticket = Ticket::factory()->create([
            'project_id' => $project->id,
            'reporter_id' => $reporterId,
            'assignee_id' => $assigneeId,
            'status' => TicketStatusEnum::open->value,
        ]);

        $ticketComment = Comment::factory()->create([
            'user_id' => $this->user->id,
            'commentable_id' => $ticket->id,
            'commentable_type' => $ticket::class,
        ]);

        $response = $this->actingAs($this->user)->delete('tickets/'.$ticket->id.'/comments/'.$ticketComment->id);

        $response->assertStatus(200);
        $response->assertJsonPath('message', __('messages.comment.delete'));

        $this->assertSoftDeleted($ticketComment);

        // Milestone
        $project = Project::factory()->create([
            'client_id' => Client::factory()->create([
                'address_id' => Address::factory()->create()->first()->id,
                'social_network_id' => SocialNetwork::factory()->create()->first()->id,
            ])->id,
            'status' => ProjectStatusEnum::active->value,
        ]);

        [$ownerId] = User::factory()->create([
            'address_id' => Address::factory(1)->create()->first()->id,
            'role_id' => RoleEnum::employee,
        ])->pluck('id');
        $project->team()->attach([$ownerId]);

        $milestone = Milestone::factory()->create([
            'project_id' => $project->id,
            'owner_id' => $ownerId,
        ]);

        $milestoneComment = Comment::factory()->create([
            'user_id' => $this->user->id,
            'commentable_id' => $milestone->id,
            'commentable_type' => $milestone::class,
        ]);

        $response = $this->actingAs($this->user)->delete('projects/'.$milestone->project->id.'/milestones/'.$milestone->id.'/comments/'.$milestoneComment->id);

        $response->assertStatus(200);
        $response->assertJsonPath('message', __('messages.comment.delete'));

        $this->assertSoftDeleted($milestoneComment);
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

        // Ticket
        $project = Project::factory()->create([
            'client_id' => Client::factory()->create([
                'address_id' => Address::factory()->create()->first()->id,
                'social_network_id' => SocialNetwork::factory()->create()->first()->id,
            ])->id,
            'status' => ProjectStatusEnum::active->value,
        ]);

        [$reporterId, $assigneeId] = User::factory(2)->create([
            'address_id' => Address::factory(1)->create()->first()->id,
            'role_id' => RoleEnum::employee,
        ])->pluck('id');
        $project->team()->attach([$reporterId, $assigneeId]);

        $ticket = Ticket::factory()->create([
            'project_id' => $project->id,
            'reporter_id' => $reporterId,
            'assignee_id' => $assigneeId,
            'status' => TicketStatusEnum::open->value,
        ]);

        $ticketComment = Comment::factory()->create([
            'user_id' => $this->user->id,
            'commentable_id' => $ticket->id,
            'commentable_type' => $ticket::class,
        ]);

        [$ticketCommentFile1, $ticketCommentFile2] = ['ticket_comment_1.jpg', 'ticket_comment_2.jpg'];

        $editedTicketComment = [
            'content' => 'Updated comments with files',
            'files' => [
                UploadedFile::fake()->image($ticketCommentFile1),
                UploadedFile::fake()->image($ticketCommentFile2),
            ],
        ];

        $response = $this->actingAs($this->user)->put('tickets/'.$ticket->id.'/comments/'.$ticketComment->id, $editedTicketComment);

        $response->assertStatus(302);
        $response->assertRedirect('tickets/'.$ticket->id);

        $lastTicketComment = Comment::where('commentable_id', $ticket->id)->where('commentable_type', $ticket::class)->latest()->first();
        $lastTicketCommentFiles = File::where('fileable_id', $lastTicketComment->id)->where('fileable_type', $lastTicketComment::class)->latest()->get();
        $this->assertEquals(2, $lastTicketCommentFiles->count());
        $this->assertEquals($ticketCommentFile1, $lastTicketCommentFiles[0]->file_name);
        $this->assertEquals('comments', $lastTicketCommentFiles[0]->collection);
        $this->assertEquals($ticketCommentFile2, $lastTicketCommentFiles[1]->file_name);
        $this->assertEquals('comments', $lastTicketCommentFiles[1]->collection);

        // Milestone
        $project = Project::factory()->create([
            'client_id' => Client::factory()->create([
                'address_id' => Address::factory()->create()->first()->id,
                'social_network_id' => SocialNetwork::factory()->create()->first()->id,
            ])->id,
            'status' => ProjectStatusEnum::active->value,
        ]);

        [$ownerId] = User::factory()->create([
            'address_id' => Address::factory(1)->create()->first()->id,
            'role_id' => RoleEnum::employee,
        ])->pluck('id');
        $project->team()->attach([$ownerId]);

        $milestone = Milestone::factory()->create([
            'project_id' => $project->id,
            'owner_id' => $ownerId,
        ]);

        $milestoneComment = Comment::factory()->create([
            'user_id' => $this->user->id,
            'commentable_id' => $milestone->id,
            'commentable_type' => $milestone::class,
        ]);

        [$milestoneCommentFile1, $milestoneCommentFile2] = ['milestone_comment_1.jpg', 'milestone_comment_2.jpg'];

        $editedMilestoneComment = [
            'content' => 'Updated comments with files',
            'files' => [
                UploadedFile::fake()->image($milestoneCommentFile1),
                UploadedFile::fake()->image($milestoneCommentFile2),
            ],
        ];

        $response = $this->actingAs($this->user)->put('projects/'.$milestone->project->id.'/milestones/'.$milestone->id.'/comments/'.$milestoneComment->id, $editedMilestoneComment);

        $response->assertStatus(302);
        $response->assertRedirect('projects/'.$milestone->project->id.'/milestones/'.$milestone->id);

        $lastMilestoneComment = Comment::where('commentable_id', $milestone->id)->where('commentable_type', $milestone::class)->latest()->first();
        $lastMilestoneCommentFiles = File::where('fileable_id', $lastMilestoneComment->id)->where('fileable_type', $lastTicketComment::class)->latest()->get();
        $this->assertEquals(2, $lastMilestoneCommentFiles->count());
        $this->assertEquals($milestoneCommentFile1, $lastMilestoneCommentFiles[0]->file_name);
        $this->assertEquals('comments', $lastMilestoneCommentFiles[0]->collection);
        $this->assertEquals($milestoneCommentFile2, $lastMilestoneCommentFiles[1]->file_name);
        $this->assertEquals('comments', $lastMilestoneCommentFiles[1]->collection);
    }

    private function createUser(): User
    {
        return User::factory()->create([
            'address_id' => Address::factory(1)->create()->first()->id,
            'role_id' => RoleEnum::boss,
        ]);
    }
}
