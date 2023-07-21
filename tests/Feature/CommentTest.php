<?php

namespace Tests\Feature;

use App\Enums\RoleEnum;
use App\Models\Address;
use App\Models\Client;
use App\Models\Comment;
use App\Models\SocialNetwork;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

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

        // TODO:: Project
        // TODO:: Milestone
        // TODO:: Task
        // TODO:: Ticket
    }

    public function test_user_can_update_comment_for_different_models(): void
    {
        // Client
        $client = Client::factory()->create([
            'address_id' => Address::factory()->create()->first()->id,
            'social_network_id' => SocialNetwork::factory()->create()->first()->id,
        ]);

        $clientComment =  Comment::factory()->create([
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

        // TODO:: Project
        // TODO:: Milestone
        // TODO:: Task
        // TODO:: Ticket
    }

    public function test_user_can_delete_comment_for_different_models(): void
    {
        // Client
        $client = Client::factory()->create([
            'address_id' => Address::factory()->create()->first()->id,
            'social_network_id' => SocialNetwork::factory()->create()->first()->id,
        ]);

        $clientComment =  Comment::factory()->create([
            'user_id' => $this->user->id,
            'commentable_id' => $client->id,
            'commentable_type' => $client::class,
        ]);

        $response = $this->actingAs($this->user)->delete('clients/'.$client->id.'/comments/'.$clientComment->id);

        $response->assertStatus(200);
        $response->assertJsonPath('message', __('messages.comment.delete'));

        $this->assertSoftDeleted($clientComment);

        // TODO:: Project
        // TODO:: Milestone
        // TODO:: Task
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
