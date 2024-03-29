<?php

namespace Tests\Feature;

use App\Enums\RoleEnum;
use App\Models\Address;
use App\Models\Client;
use App\Models\Comment;
use App\Models\File;
use App\Models\Note;
use App\Models\SocialNetwork;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class ClientTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    public function setUp(): void
    {
        parent::setUp();

        $this->seed();

        $this->user = $this->createUser();
    }

    public function test_user_can_see_clients_index(): void
    {
        $response = $this->actingAs($this->user)->get('clients');

        $response->assertStatus(200);
        $response->assertSee('Clients');

        $this->assertDatabaseCount('clients', 0);
    }

    public function test_user_can_see_client_show(): void
    {
        $client = $this->createClient();

        // Show
        $response = $this->actingAs($this->user)->get('clients/'.$client->id);

        $response->assertStatus(200);
        $response->assertSeeText($client->name);

        // Comments
        $response = $this->actingAs($this->user)->get('clients/'.$client->id.'/comments');

        $response->assertStatus(200);
        $response->assertSee('name="files[]"', false);

        // Files
        $response = $this->actingAs($this->user)->get('clients/'.$client->id.'/files');

        $response->assertStatus(200);
        $response->assertSeeText('No files were uploaded!');

        // Notes
        $response = $this->actingAs($this->user)->get('clients/'.$client->id.'/notes');

        $response->assertStatus(200);
        $response->assertSeeText('No notes were found!');
    }

    public function test_user_can_mark_client(): void
    {
        $client = $this->createClient();

        // Mark Client
        $response = $this->actingAs($this->user)->patch('clients/'.$client->id.'/mark');

        $response->assertStatus(200);

        $response->assertJsonPath('message', __('messages.client.mark'));
        $response->assertJsonPath('client.id', $client->id);

        $markedClient = Client::find($client->id);
        $this->assertEquals($markedClient->is_marked, true);

        // Unmark client
        $response = $this->actingAs($this->user)->patch('clients/'.$client->id.'/mark');

        $response->assertStatus(200);

        $response->assertJsonPath('message', __('messages.client.unmark'));
        $response->assertJsonPath('client.id', $client->id);

        $markedClient = Client::find($client->id);
        $this->assertEquals($markedClient->is_marked, false);
    }

    public function test_user_can_get_to_create_client_page(): void
    {
        $response = $this->actingAs($this->user)->get('clients/create');

        $response->assertStatus(200);
        $response->assertSee('Create Client');
    }

    public function test_user_can_store_client(): void
    {
        $client = $this->getClientArray();

        $response = $this->actingAs($this->user)->post('clients', $client);

        $lastClient = Client::latest()->first();

        $response->assertStatus(302);
        $response->assertRedirect('clients/'.$lastClient->id);

        $this->assertDatabaseHas('clients', [
            'id' => $lastClient->id,
        ]);

        $this->assertEquals($client['name'], $lastClient->name);
        $this->assertEquals($client['email'], $lastClient->email);
        $this->assertEquals($client['note'], $lastClient->note);
        $this->assertEquals($client['country'], $lastClient->address->country);
        $this->assertEquals($client['twitter'], $lastClient->socialNetwork->twitter);
    }

    public function test_user_can_get_to_edit_client_page(): void
    {
        $client = $this->createClient();

        $response = $this->actingAs($this->user)->get('clients/'.$client->id.'/edit');

        $response->assertStatus(200);
        $response->assertSee('Edit Client');
        $response->assertSee('value="'.$client->name.'"', false);
        $response->assertSee('value="'.$client->address->street.'"', false);
        $response->assertSee('value="'.$client->socialNetwork->twitter.'"', false);
        $response->assertSee('>'.$client->note.'</textarea>', false);
        $response->assertViewHas('client', $client);
    }

    public function test_user_can_update_client(): void
    {
        $client = $this->createClient();
        $editedClient = $this->getClientArray();

        $response = $this->actingAs($this->user)->put('clients/'.$client->id, $editedClient);

        $response->assertStatus(302);
        $response->assertRedirect('clients/'.$client->id);

        $updatedClient = Client::find($client->id);

        $this->assertEquals($editedClient['name'], $updatedClient->name);
        $this->assertEquals($editedClient['note'], $updatedClient->note);
        $this->assertEquals($editedClient['twitter'], $updatedClient->socialNetwork->twitter);
        $this->assertEquals($editedClient['street'], $updatedClient->address->street);
    }

    public function test_user_can_work_with_client_logo(): void
    {
        $client = $this->createClient();
        $editedClient = $this->getClientArray();

        $clientFileName = 'client.jpg';
        $editedClient['logo'] = UploadedFile::fake()->image($clientFileName);

        // Upload
        $response = $this->actingAs($this->user)->put('clients/'.$client->id, $editedClient);

        $response->assertStatus(302);
        $response->assertRedirect('clients/'.$client->id);

        $lastLogoFile = File::latest()->first();
        $this->assertEquals($clientFileName, $lastLogoFile->file_name);
        $this->assertEquals('clients/logos', $lastLogoFile->collection);

        // Remove
        $response = $this->actingAs($this->user)->delete('clients/'.$client->id.'/logo/remove');

        $response->assertStatus(200);
        $response->assertJsonPath('message', __('messages.client.logo.delete'));

        $this->assertEquals($client->logo_id, null);
    }

    public function test_user_can_delete_client(): void
    {
        $client = $this->createClient();

        $response = $this->actingAs($this->user)->delete('clients/'.$client->id);

        $response->assertStatus(200);
        $response->assertJsonPath('message', __('messages.client.delete'));

        $this->assertSoftDeleted($client);
    }

    public function test_user_can_upload_file_for_client(): void
    {
        $client = $this->createClient();

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
    }

    public function test_user_can_delete_file_for_client(): void
    {
        $client = $this->createClient();

        $clientFile = File::factory()->create([
            'fileable_id' => $client->id,
            'fileable_type' => $client::class,
        ]);

        $response = $this->actingAs($this->user)->delete('clients/'.$client->id.'/files/'.$clientFile->id);

        $response->assertStatus(200);
        $response->assertJsonPath('message', __('messages.file.delete'));

        $this->assertSoftDeleted($clientFile);
    }

    public function test_user_can_store_note_for_client(): void
    {
        $client = $this->createClient();

        $clientNote = [
            'user_id' => $this->user->id,
            'name' => 'Note',
            'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
            'noteable_id' => $client->id,
            'noteable_type' => $client::class,
        ];

        $response = $this->actingAs($this->user)->post('clients/'.$client->id.'/notes', $clientNote);

        $response->assertStatus(302);
        $response->assertRedirect('clients/'.$client->id.'/notes');

        $this->assertDatabaseHas('notes', $clientNote);

        $lastClientNote = Note::where('noteable_id', $client->id)->where('noteable_type', $client::class)->latest()->first();
        $this->assertEquals($clientNote['noteable_id'], $lastClientNote->noteable_id);
        $this->assertEquals($clientNote['noteable_type'], $lastClientNote->noteable_type);
        $this->assertEquals($clientNote['content'], $lastClientNote->content);
    }

    public function test_user_can_update_note_for_client(): void
    {
        $client = $this->createClient();

        $clientNote = Note::factory()->create([
            'user_id' => $this->user->id,
            'noteable_id' => $client->id,
            'noteable_type' => $client::class,
        ]);

        $editedClientNote = [
            'name' => 'Updated Client Note',
            'content' => 'Updated Client Note Content',
        ];

        $response = $this->actingAs($this->user)->put('clients/'.$client->id.'/notes/'.$clientNote->id, $editedClientNote);

        $response->assertStatus(302);
        $response->assertRedirect('clients/'.$client->id.'/notes');

        $lastClientNote = Note::where('noteable_id', $client->id)->where('noteable_type', $client::class)->latest()->first();
        $this->assertEquals($editedClientNote['content'], $lastClientNote->content);
    }

    public function test_user_can_store_clients_comment(): void
    {
        $client = $this->createClient();

        $clientComment = [
            'commentable_id' => $client->id,
            'commentable_type' => $client::class,
            'user_id' => $this->user->id,
            'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
        ];

        $response = $this->actingAs($this->user)->post('clients/'.$client->id.'/comments', $clientComment);

        $response->assertStatus(302);
        $response->assertRedirect('clients/'.$client->id.'/comments');

        $this->assertDatabaseHas('comments', $clientComment);

        $lastComment = Comment::where('commentable_id', $client->id)->where('commentable_type', $client::class)->latest()->first();
        $this->assertEquals($clientComment['commentable_id'], $lastComment->commentable_id);
        $this->assertEquals($clientComment['commentable_type'], $lastComment->commentable_type);
        $this->assertEquals($clientComment['content'], $lastComment->content);
    }

    public function test_user_can_update_clients_comment(): void
    {
        $client = $this->createClient();

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
    }

    public function test_user_can_delete_clients_comment(): void
    {
        $client = $this->createClient();

        $clientComment = Comment::factory()->create([
            'user_id' => $this->user->id,
            'commentable_id' => $client->id,
            'commentable_type' => $client::class,
        ]);

        $response = $this->actingAs($this->user)->delete('clients/'.$client->id.'/comments/'.$clientComment->id);

        $response->assertStatus(200);
        $response->assertJsonPath('message', __('messages.comment.delete'));

        $this->assertSoftDeleted($clientComment);
    }

    public function test_user_can_upload_files_to_clients_comment(): void
    {
        $client = $this->createClient();

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
    }

    private function createUser(): User
    {
        return User::factory()->create([
            'address_id' => Address::factory(1)->create()->first()->id,
            'role_id' => RoleEnum::boss,
        ]);
    }

    private function createClient(): Client
    {
        return Client::factory()->create([
            'address_id' => Address::factory()->create()->first()->id,
            'social_network_id' => SocialNetwork::factory()->create()->first()->id,
        ]);
    }

    private function getClientArray(): array
    {
        return [
            'name' => 'Client',
            'email' => 'client@test.com',
            'contact_person' => 'Tester',
            'contact_email' => 'tester@test.com',
            'mobile' => '(888) 937-7238',
            'phone' => '201-886-0269 x3767',
            'note' => 'Aenean sed pulvinar velit. Quisque suscipit, leo sit amet facilisis malesuada, neque eros lacinia sapien, eu gravida risus tellus ut enim.',
            'street' => 'Keegan Trail',
            'house_number' => '484',
            'city' => 'West Judge',
            'country' => 'Falkland Islands',
            'zip_code' => '2001',
            'website' => 'https://www.google.com/',
            'skype' => 'https://www.skype.com/',
            'linkedin' => 'https://www.linkedin.com/',
            'twitter' => 'https://www.twitter.com/',
            'facebook' => 'https://www.facebook.com/',
            'instagram' => 'https://www.instagram.com/',
        ];
    }
}
