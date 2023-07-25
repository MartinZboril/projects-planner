<?php

namespace Tests\Feature;

use App\Enums\ProjectStatusEnum;
use App\Enums\RoleEnum;
use App\Enums\TicketPriorityEnum;
use App\Enums\TicketStatusEnum;
use App\Enums\TicketTypeEnum;
use App\Events\Ticket\Status\TicketArchived;
use App\Events\Ticket\Status\TicketClosed;
use App\Events\Ticket\Status\TicketConverted;
use App\Events\Ticket\Status\TicketReopened;
use App\Events\Ticket\TicketAssigneeChanged;
use App\Models\Address;
use App\Models\Client;
use App\Models\Comment;
use App\Models\File;
use App\Models\Project;
use App\Models\SocialNetwork;
use App\Models\Task;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class TicketTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    public function setUp(): void
    {
        parent::setUp();

        $this->seed();

        $this->user = $this->createUser();
    }

    public function test_user_can_see_tickets_index(): void
    {
        $response = $this->actingAs($this->user)->get('tickets');

        $response->assertStatus(200);
        $response->assertSee('Tickets');

        $this->assertDatabaseCount('tickets', 0);
    }

    public function test_user_can_see_tickets_show(): void
    {
        $this->actingAs($this->user);

        $ticket = $this->createTicket();

        // Show
        $response = $this->actingAs($this->user)->get('tickets/'.$ticket->id);
        $response->assertStatus(200);
        $response->assertSeeText($ticket->name);
    }

    public function test_user_can_get_to_create_ticket_page(): void
    {
        $response = $this->actingAs($this->user)->get('tickets/create');

        $response->assertStatus(200);
        $response->assertSee('Create Ticket');
    }

    public function test_user_can_store_ticket(): void
    {
        Event::fake();

        $ticket = $this->getTicketArray();

        $response = $this->actingAs($this->user)->post('tickets', $ticket);

        $lastTicket = Ticket::latest()->first();

        $response->assertStatus(302);
        $response->assertRedirect('tickets/'.$lastTicket->id);

        $this->assertDatabaseHas('tickets', [
            'id' => $lastTicket->id,
        ]);

        $this->assertEquals($ticket['subject'], $lastTicket->subject);
        $this->assertEquals($ticket['dued_at'], $lastTicket->dued_at->format('Y-m-d'));
        $this->assertEquals($ticket['message'], $lastTicket->message);
        $this->assertEquals($ticket['project_id'], $lastTicket->project->id);
        $this->assertEquals($ticket['assignee_id'], $lastTicket->assignee->id);
        $this->assertEquals(TicketStatusEnum::open, $lastTicket->status);

        if ($lastTicket->assignee->id ?? false) {
            Event::assertDispatched(TicketAssigneeChanged::class);
        }
    }

    public function test_user_can_get_to_edit_ticket_page(): void
    {
        $ticket = $this->createTicket();

        $response = $this->actingAs($this->user)->get('tickets/'.$ticket->id.'/edit');

        $response->assertStatus(200);
        $response->assertSee('Edit Ticket');
        $response->assertSee('value="'.$ticket->subject.'"', false);
        $response->assertSee('value="'.$ticket->dued_at->format('Y-m-d').'"', false);
        $response->assertSee('>'.$ticket->message.'</textarea>', false);
        $response->assertViewHas('ticket', $ticket);
    }

    public function test_user_can_update_ticket(): void
    {
        Event::fake();

        $ticket = $this->createTicket();
        $editedTicket = $this->getTicketArray();

        $response = $this->actingAs($this->user)->put('tickets/'.$ticket->id, $editedTicket);

        $response->assertStatus(302);
        $response->assertRedirect('tickets/'.$ticket->id);

        $updatedTicket = Ticket::find($ticket->id);

        $this->assertEquals($editedTicket['subject'], $updatedTicket->subject);
        $this->assertEquals($editedTicket['message'], $updatedTicket->message);
        $this->assertNotEquals($editedTicket['reporter_id'], $updatedTicket->reporter->id);
        $this->assertEquals($editedTicket['assignee_id'], $updatedTicket->assignee->id);

        if (($updatedTicket->assignee->id ?? false) && $ticket->assignee->id !== $updatedTicket->assignee->id) {
            Event::assertDispatched(TicketAssigneeChanged::class);
        }
    }

    public function test_user_can_mark_ticket(): void
    {
        $ticket = $this->createTicket();

        // Mark ticket
        $response = $this->actingAs($this->user)->patch('tickets/'.$ticket->id.'/mark');

        $response->assertStatus(200);

        $response->assertJsonPath('message', __('messages.ticket.mark'));
        $response->assertJsonPath('ticket.id', $ticket->id);

        $markedTicket = Ticket::find($ticket->id);
        $this->assertEquals($markedTicket->is_marked, true);

        // Unmark ticket
        $response = $this->actingAs($this->user)->patch('tickets/'.$ticket->id.'/mark');

        $response->assertStatus(200);

        $response->assertJsonPath('message', __('messages.ticket.unmark'));
        $response->assertJsonPath('ticket.id', $ticket->id);

        $markedTicket = Ticket::find($ticket->id);
        $this->assertEquals($markedTicket->is_marked, false);
    }

    public function test_user_can_change_ticket_status(): void
    {
        Event::fake();

        $ticket = $this->createTicket();

        $this->assertEquals(TicketStatusEnum::open->value, $ticket->status->value);

        // Close
        $response = $this->actingAs($this->user)->patch('tickets/'.$ticket->id.'/change-status', [
            'status' => TicketStatusEnum::close->value,
        ]);

        $response->assertStatus(200);

        $response->assertJsonPath('message', __('messages.ticket.'.TicketStatusEnum::close->name));
        $response->assertJsonPath('ticket.id', $ticket->id);
        $response->assertJsonPath('ticket.status', TicketStatusEnum::close->value);

        Event::assertDispatched(TicketClosed::class);

        // Reopen
        $response = $this->actingAs($this->user)->patch('tickets/'.$ticket->id.'/change-status', [
            'status' => TicketStatusEnum::open->value,
        ]);

        $response->assertStatus(200);

        $response->assertJsonPath('message', __('messages.ticket.'.TicketStatusEnum::open->name));
        $response->assertJsonPath('ticket.id', $ticket->id);
        $response->assertJsonPath('ticket.status', TicketStatusEnum::open->value);

        Event::assertDispatched(TicketReopened::class);

        // Archive
        $response = $this->actingAs($this->user)->patch('tickets/'.$ticket->id.'/change-status', [
            'status' => TicketStatusEnum::archive->value,
        ]);

        $response->assertStatus(200);

        $response->assertJsonPath('message', __('messages.ticket.'.TicketStatusEnum::archive->name));
        $response->assertJsonPath('ticket.id', $ticket->id);
        $response->assertJsonPath('ticket.status', TicketStatusEnum::archive->value);

        Event::assertDispatched(TicketArchived::class);
    }

    public function test_user_can_convert_ticket_into_task(): void
    {
        Event::fake();

        $ticket = $this->createTicket();

        $response = $this->actingAs($this->user)->patch('tickets/'.$ticket->id.'/convert');

        $response->assertStatus(200);

        $convertedTicket = Ticket::find($ticket->id)->first();
        $this->assertEquals(TicketStatusEnum::convert->value, $convertedTicket->status->value);

        $createdTask = Task::where('ticket_id', $ticket->id)->first();
        $response->assertJsonPath('redirect', route('tasks.show', $createdTask->id));
        $this->assertEquals($convertedTicket->id, $createdTask->ticket->id);

        Event::assertDispatched(TicketConverted::class);
    }

    public function test_user_can_delete_ticket(): void
    {
        $ticket = $this->createTicket();

        $response = $this->actingAs($this->user)->delete('tickets/'.$ticket->id);

        $response->assertStatus(200);
        $response->assertJsonPath('message', __('messages.ticket.delete'));

        $this->assertSoftDeleted($ticket);
    }

    public function test_user_can_upload_file_for_ticket(): void
    {
        $ticket = $this->createTicket();

        $ticketFileName = 'ticket.jpg';

        $ticketFiles = [
            'fileable_id' => $ticket->id,
            'fileable_type' => $ticket::class,
            'files' => [
                UploadedFile::fake()->image($ticketFileName),
            ],
        ];

        $response = $this->actingAs($this->user)->post('tickets/'.$ticket->id.'/files/upload', $ticketFiles);

        $response->assertStatus(302);
        $response->assertRedirect('tickets/'.$ticket->id);

        $lastTicketFile = File::where('fileable_id', $ticket->id)->where('fileable_type', $ticket::class)->latest()->first();
        $this->assertEquals($ticketFiles['fileable_id'], $lastTicketFile->fileable_id);
        $this->assertEquals($ticketFiles['fileable_type'], $lastTicketFile->fileable_type);
        $this->assertEquals($ticketFileName, $lastTicketFile->file_name);
        $this->assertEquals('tickets/files', $lastTicketFile->collection);
    }

    public function test_user_can_delete_file_for_ticket(): void
    {
        $ticket = $this->createTicket();

        $ticketFile = File::factory()->create([
            'fileable_id' => $ticket->id,
            'fileable_type' => $ticket::class,
        ]);

        $response = $this->actingAs($this->user)->delete('tickets/'.$ticket->id.'/files/'.$ticketFile->id);

        $response->assertStatus(200);
        $response->assertJsonPath('message', __('messages.file.delete'));

        $this->assertSoftDeleted($ticketFile);

    }

    public function test_user_can_store_tickets_comment(): void
    {
        $ticket = $this->createTicket();

        $ticketComment = [
            'commentable_id' => $ticket->id,
            'commentable_type' => $ticket::class,
            'user_id' => $this->user->id,
            'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
        ];

        $response = $this->actingAs($this->user)->post('tickets/'.$ticket->id.'/comments', $ticketComment);

        $response->assertStatus(302);
        $response->assertRedirect('tickets/'.$ticket->id);

        $this->assertDatabaseHas('comments', $ticketComment);

        $lastTicket = Comment::where('commentable_id', $ticket->id)->where('commentable_type', $ticket::class)->latest()->first();
        $this->assertEquals($ticketComment['commentable_id'], $lastTicket->commentable_id);
        $this->assertEquals($ticketComment['commentable_type'], $lastTicket->commentable_type);
        $this->assertEquals($ticketComment['content'], $lastTicket->content);
    }

    public function test_user_can_update_tickets_comment(): void
    {
        $ticket = $this->createTicket();

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
    }

    public function test_user_can_delete_tickets_comment(): void
    {
        $ticket = $this->createTicket();

        $ticketComment = Comment::factory()->create([
            'user_id' => $this->user->id,
            'commentable_id' => $ticket->id,
            'commentable_type' => $ticket::class,
        ]);

        $response = $this->actingAs($this->user)->delete('tickets/'.$ticket->id.'/comments/'.$ticketComment->id);

        $response->assertStatus(200);
        $response->assertJsonPath('message', __('messages.comment.delete'));

        $this->assertSoftDeleted($ticketComment);
    }

    public function test_user_can_upload_files_to_tickets_comment(): void
    {
        $ticket = $this->createTicket();

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
    }

    private function createUser(): User
    {
        return User::factory()->create([
            'address_id' => Address::factory(1)->create()->first()->id,
            'role_id' => RoleEnum::boss,
        ]);
    }

    private function createTicket(): Ticket
    {
        $this->actingAs($this->user);

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

        return Ticket::factory()->create([
            'project_id' => $project->id,
            'reporter_id' => $reporterId,
            'assignee_id' => $assigneeId,
            'status' => TicketStatusEnum::open->value,
        ]);
    }

    private function getTicketArray(): array
    {
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

        return [
            'project_id' => $project->id,
            'reporter_id' => $reporterId,
            'assignee_id' => $assigneeId,
            'status' => TicketStatusEnum::open->value,
            'priority' => TicketPriorityEnum::medium->value,
            'type' => TicketTypeEnum::inovation->value,
            'subject' => 'Ticket',
            'dued_at' => '2023-07-10',
            'message' => 'Aenean sed pulvinar velit. Quisque suscipit, leo sit amet facilisis malesuada, neque eros lacinia sapien, eu gravida risus tellus ut enim.',
        ];
    }
}
