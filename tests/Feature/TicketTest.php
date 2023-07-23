<?php

namespace Tests\Feature;

use App\Enums\ProjectStatusEnum;
use App\Enums\RoleEnum;
use App\Enums\TicketPriorityEnum;
use App\Enums\TicketStatusEnum;
use App\Enums\TicketTypeEnum;
use App\Models\Address;
use App\Models\Client;
use App\Models\Project;
use App\Models\SocialNetwork;
use App\Models\Task;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
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

        // Reopen
        $response = $this->actingAs($this->user)->patch('tickets/'.$ticket->id.'/change-status', [
            'status' => TicketStatusEnum::open->value,
        ]);

        $response->assertStatus(200);

        $response->assertJsonPath('message', __('messages.ticket.'.TicketStatusEnum::open->name));
        $response->assertJsonPath('ticket.id', $ticket->id);
        $response->assertJsonPath('ticket.status', TicketStatusEnum::open->value);

        // Archive
        $response = $this->actingAs($this->user)->patch('tickets/'.$ticket->id.'/change-status', [
            'status' => TicketStatusEnum::archive->value,
        ]);

        $response->assertStatus(200);

        $response->assertJsonPath('message', __('messages.ticket.'.TicketStatusEnum::archive->name));
        $response->assertJsonPath('ticket.id', $ticket->id);
        $response->assertJsonPath('ticket.status', TicketStatusEnum::archive->value);
    }

    public function test_user_can_convert_ticket_into_task(): void
    {
        $ticket = $this->createTicket();

        $response = $this->actingAs($this->user)->patch('tickets/'.$ticket->id.'/convert');

        $response->assertStatus(200);

        $convertedTicket = Ticket::find($ticket->id)->first();
        $this->assertEquals(TicketStatusEnum::convert->value, $convertedTicket->status->value);

        $createdTask = Task::where('ticket_id', $ticket->id)->first();
        $response->assertJsonPath('redirect', route('tasks.show', $createdTask->id));
        $this->assertEquals($convertedTicket->id, $createdTask->ticket->id);
    }

    public function test_user_can_delete_ticket(): void
    {
        $ticket = $this->createTicket();

        $response = $this->actingAs($this->user)->delete('tickets/'.$ticket->id);

        $response->assertStatus(200);
        $response->assertJsonPath('message', __('messages.ticket.delete'));

        $this->assertSoftDeleted($ticket);
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

    private function createUser(): User
    {
        return User::factory()->create([
            'address_id' => Address::factory(1)->create()->first()->id,
            'role_id' => RoleEnum::boss,
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
