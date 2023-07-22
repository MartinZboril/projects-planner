<?php

namespace Tests\Feature;

use App\Enums\RoleEnum;
use App\Models\Address;
use App\Models\Client;
use App\Models\File;
use App\Models\SocialNetwork;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

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

        //TODO: Project
        //TODO: Milestone
        //TODO: Task
        //TODO: Ticket
        //TODO: Comment
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

        //TODO: Project
        //TODO: Milestone
        //TODO: Task
        //TODO: Ticket
        //TODO: Comment
    }

    private function createUser(): User
    {
        return User::factory()->create([
            'address_id' => Address::factory(1)->create()->first()->id,
            'role_id' => RoleEnum::boss,
        ]);
    }
}
