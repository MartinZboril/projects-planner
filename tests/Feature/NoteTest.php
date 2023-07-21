<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Note;
use App\Models\User;
use App\Models\Client;
use App\Enums\RoleEnum;
use App\Models\Address;
use App\Models\SocialNetwork;
use Illuminate\Foundation\Testing\RefreshDatabase;

class NoteTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    public function setUp(): void
    {
        parent::setUp();

        $this->seed();

        $this->user = $this->createUser();
    }

    public function test_user_can_see_empty_list(): void
    {
        $response = $this->actingAs($this->user)->get('notes');

        $response->assertStatus(200);
        $response->assertSee('Notes');
        $response->assertSee('No notes were found!');

        $this->assertDatabaseCount('notes', 0);
    }

    public function test_user_can_see_list_with_notes(): void
    {
        [$note1, $note2] = Note::factory(2)->create(['user_id' => $this->user->id]);

        $response = $this->actingAs($this->user)->get('notes');

        $response->assertStatus(200);
        $response->assertSee('Notes');
        $response->assertSeeInOrder([$note1->name, $note2->name]);

        $this->assertDatabaseCount('notes', 2);
    }

    public function test_user_can_mark_note(): void
    {
        $note = Note::factory()->create(['user_id' => $this->user->id]);

        $response = $this->actingAs($this->user)->patch('notes/'.$note->id.'/mark', [
            'redirect' => route('notes.index'),
        ]);

        $response->assertStatus(200);
        $response->assertJsonPath('redirect', route('notes.index'));

        $markedNote = Note::find($note->id);
        $this->assertEquals($markedNote->is_marked, true);
    }

    public function test_user_can_see_list_marked_notes(): void
    {
        $unmarkedNote = Note::factory()->create(['user_id' => $this->user->id]);
        $markedNote = Note::factory()->create([
            'user_id' => $this->user->id,
            'is_marked' => true,
        ]);

        $response = $this->actingAs($this->user)->get('notes');

        $response->assertStatus(200);
        $response->assertSee('Notes');
        $response->assertSeeInOrder([$markedNote->name, $unmarkedNote->name]);

        $this->assertDatabaseCount('notes', 2);
    }

    public function test_user_can_get_to_create_note_page(): void
    {
        $response = $this->actingAs($this->user)->get('notes/create');

        $response->assertStatus(200);
        $response->assertSee('Create Note');
    }

    public function test_user_can_store_note(): void
    {
        $note = [
            'user_id' => $this->user->id,
            'name' => 'Note',
            'is_private' => true,
            'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
        ];

        $response = $this->actingAs($this->user)->post('notes', $note);

        $response->assertStatus(302);
        $response->assertRedirect('notes');

        $this->assertDatabaseHas('notes', $note);

        $lastNote = Note::latest()->first();
        $this->assertEquals($note['name'], $lastNote->name);
        $this->assertEquals($note['content'], $lastNote->content);
    }

    public function test_user_can_store_note_for_different_models(): void
    {
        $note = [
            'user_id' => $this->user->id,
            'name' => 'Note',
            'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
        ];

        // Client
        $client = Client::factory()->create([
            'address_id' => Address::factory()->create()->first()->id,
            'social_network_id' => SocialNetwork::factory()->create()->first()->id,
        ]);

        $clientNote = $note + [
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

        //TODO: Project
    }

    public function test_user_can_get_to_edit_note_page(): void
    {
        $note = Note::factory()->create(['user_id' => $this->user->id]);

        $response = $this->actingAs($this->user)->get('notes/'.$note->id.'/edit');

        $response->assertStatus(200);
        $response->assertSee('Edit Note');
        $response->assertSee('value="'.$note->name.'"', false);
        $response->assertSee('>'.$note->content.'</textarea>', false);
        $response->assertViewHas('note', $note);
    }

    public function test_user_can_update_note(): void
    {
        $note = Note::factory()->create(['user_id' => $this->user->id]);
        $editedNote = [
            'name' => 'Updated Note',
            'content' => 'Aenean sed pulvinar velit. Quisque suscipit, leo sit amet facilisis malesuada, neque eros lacinia sapien, eu gravida risus tellus ut enim.',
        ];

        $response = $this->actingAs($this->user)->put('notes/'.$note->id, $editedNote);

        $response->assertStatus(302);
        $response->assertRedirect('notes');

        $updatedNote = Note::find($note->id);

        $this->assertEquals($editedNote['name'], $updatedNote->name);
        $this->assertEquals($editedNote['content'], $updatedNote->content);
    }

    public function test_user_can_update_note_for_different_models(): void
    {
        // Client
        $client = Client::factory()->create([
            'address_id' => Address::factory()->create()->first()->id,
            'social_network_id' => SocialNetwork::factory()->create()->first()->id,
        ]);

        $clientNote =  Note::factory()->create([
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

        // TODO:: Project
    }

    public function test_user_can_delete_note(): void
    {
        $note = Note::factory()->create(['user_id' => $this->user->id]);

        $response = $this->actingAs($this->user)->delete('notes/'.$note->id);

        $response->assertStatus(200);
        $response->assertJsonPath('message', __('messages.note.delete'));

        $this->assertSoftDeleted($note);
    }

    public function test_user_can_delete_note_for_different_models(): void
    {
        // Client
        $client = Client::factory()->create([
            'address_id' => Address::factory()->create()->first()->id,
            'social_network_id' => SocialNetwork::factory()->create()->first()->id,
        ]);

        $clientNote =  Note::factory()->create([
            'user_id' => $this->user->id,
            'noteable_id' => $client->id,
            'noteable_type' => $client::class,
        ]);

        $response = $this->actingAs($this->user)->delete('clients/'.$client->id.'/notes/'.$clientNote->id);

        $response->assertStatus(200);
        $response->assertJsonPath('message', __('messages.note.delete'));

        $this->assertSoftDeleted($clientNote);

        // TODO:: Project
    }

    private function createUser(): User
    {
        return User::factory()->create([
            'address_id' => Address::factory(1)->create()->first()->id,
            'role_id' => RoleEnum::boss,
        ]);
    }
}
