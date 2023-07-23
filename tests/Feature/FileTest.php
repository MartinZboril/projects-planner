<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\File;
use App\Models\Task;
use App\Models\User;
use App\Models\Client;
use App\Enums\RoleEnum;
use App\Models\Address;
use App\Models\Project;
use App\Enums\TaskStatusEnum;
use App\Models\SocialNetwork;
use App\Enums\ProjectStatusEnum;
use Illuminate\Http\UploadedFile;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FileTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    public function setUp(): void
    {
        parent::setUp();

        $this->seed();

        $this->user = $this->createUser();
    }

    public function test_user_can_upload_file_for_different_models(): void
    {
        // Client
        $client = Client::factory()->create([
            'address_id' => Address::factory()->create()->first()->id,
            'social_network_id' => SocialNetwork::factory()->create()->first()->id,
        ]);

        $clientFileName = 'client.jpg';

        $clientFiles = [
            'fileable_id' => $client->id,
            'fileable_type' => $client::class,
            'files' => [
                UploadedFile::fake()->image($clientFileName),
            ],
        ];

        $response = $this->actingAs($this->user)->post('clients/'.$client->id.'/files/upload', $clientFiles);

        $response->assertStatus(302);
        $response->assertRedirect('clients/'.$client->id.'/files');

        $lastClientFile = File::where('fileable_id', $client->id)->where('fileable_type', $client::class)->latest()->first();
        $this->assertEquals($clientFiles['fileable_id'], $lastClientFile->fileable_id);
        $this->assertEquals($clientFiles['fileable_type'], $lastClientFile->fileable_type);
        $this->assertEquals($clientFileName, $lastClientFile->file_name);
        $this->assertEquals('clients/files', $lastClientFile->collection);

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

        $projectFileName = 'project.jpg';

        $projectFiles = [
            'fileable_id' => $project->id,
            'fileable_type' => $project::class,
            'files' => [
                UploadedFile::fake()->image($projectFileName),
            ],
        ];

        $response = $this->actingAs($this->user)->post('projects/'.$project->id.'/files/upload', $projectFiles);

        $response->assertStatus(302);
        $response->assertRedirect('projects/'.$project->id.'/files');

        $lastProjectFile = File::where('fileable_id', $project->id)->where('fileable_type', $project::class)->latest()->first();
        $this->assertEquals($projectFiles['fileable_id'], $lastProjectFile->fileable_id);
        $this->assertEquals($projectFiles['fileable_type'], $lastProjectFile->fileable_type);
        $this->assertEquals($projectFileName, $lastProjectFile->file_name);
        $this->assertEquals('projects/files', $lastProjectFile->collection);

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

        //TODO: Milestone
        //TODO: Ticket
    }

    public function test_user_can_delete_file_for_different_models(): void
    {
        // Client
        $client = Client::factory()->create([
            'address_id' => Address::factory()->create()->first()->id,
            'social_network_id' => SocialNetwork::factory()->create()->first()->id,
        ]);

        $clientFile = File::factory()->create([
            'fileable_id' => $client->id,
            'fileable_type' => $client::class,
        ]);

        $response = $this->actingAs($this->user)->delete('clients/'.$client->id.'/files/'.$clientFile->id);

        $response->assertStatus(200);
        $response->assertJsonPath('message', __('messages.file.delete'));

        $this->assertSoftDeleted($clientFile);

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

        $projectFile = File::factory()->create([
            'fileable_id' => $project->id,
            'fileable_type' => $project::class,
        ]);

        $response = $this->actingAs($this->user)->delete('projects/'.$project->id.'/files/'.$projectFile->id);

        $response->assertStatus(200);
        $response->assertJsonPath('message', __('messages.file.delete'));

        $this->assertSoftDeleted($projectFile);

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

        $taskFile = File::factory()->create([
            'fileable_id' => $task->id,
            'fileable_type' => $task::class,
        ]);

        $response = $this->actingAs($this->user)->delete('tasks/'.$task->id.'/files/'.$taskFile->id);

        $response->assertStatus(200);
        $response->assertJsonPath('message', __('messages.file.delete'));

        $this->assertSoftDeleted($taskFile);

        //TODO: Milestone
        //TODO: Ticket
    }

    private function createUser(): User
    {
        return User::factory()->create([
            'address_id' => Address::factory(1)->create()->first()->id,
            'role_id' => RoleEnum::boss,
        ]);
    }
}
