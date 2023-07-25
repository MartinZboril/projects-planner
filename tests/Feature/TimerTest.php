<?php

namespace Tests\Feature;

use App\Enums\ProjectStatusEnum;
use App\Enums\RoleEnum;
use App\Events\Timer\TimerChanged;
use App\Events\Timer\TimerStopped;
use App\Models\Address;
use App\Models\Client;
use App\Models\Project;
use App\Models\Rate;
use App\Models\SocialNetwork;
use App\Models\Timer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class TimerTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    public function setUp(): void
    {
        parent::setUp();

        $this->seed();

        $this->user = $this->createUser();
    }

    public function test_user_can_see_timers_index_for_specific_project(): void
    {
        $project = $this->createProject(2);

        $response = $this->actingAs($this->user)->get('projects/'.$project->id.'/timers');

        $response->assertStatus(200);
        $response->assertSee('Timesheets');

        $this->assertDatabaseCount('timers', 0);
    }

    public function test_user_can_get_to_create_projects_timer_page(): void
    {
        $project = $this->createProject(2);

        $response = $this->actingAs($this->user)->get('projects/'.$project->id.'/timers/create');

        $response->assertStatus(200);
        $response->assertSee('Create Timer');
    }

    public function test_user_can_store_projects_timer(): void
    {
        Event::fake();

        $timer = $this->getTimerArray();

        $response = $this->actingAs($this->user)->post('projects/'.$timer['project_id'].'/timers', $timer);

        $lastTimer = Timer::latest()->first();

        $response->assertStatus(302);
        $response->assertRedirect('projects/'.$lastTimer->project->id.'/timers');

        $this->assertDatabaseHas('timers', [
            'id' => $lastTimer->id,
        ]);

        $this->assertEquals($lastTimer['since_at'], $lastTimer->since_at->format('Y-m-d H:i:s'));
        $this->assertEquals($lastTimer['until_at'], $lastTimer->until_at->format('Y-m-d H:i:s'));
        $this->assertEquals($lastTimer['note'], $lastTimer->note);
        $this->assertEquals($lastTimer['project_id'], $lastTimer->project->id);
        $this->assertEquals($lastTimer['user_id'], $lastTimer->user->id);
        $this->assertEquals($lastTimer['rate_id'], $lastTimer->rate->id);

        Event::assertDispatched(TimerChanged::class);
    }

    public function test_user_can_get_to_edit_projects_timer_page(): void
    {
        $timer = $this->createTimer();

        $response = $this->actingAs($this->user)->get('projects/'.$timer->project->id.'/timers/'.$timer->id.'/edit');

        $response->assertStatus(200);
        $response->assertSee('Edit Timer');
        $response->assertSee('value="'.$timer->since_at->format('Y-m-d H:i').'"', false);
        $response->assertSee('>'.$timer->note.'</textarea>', false);
        $response->assertViewHas('timer', $timer);
    }

    public function test_user_can_update_projects_timer(): void
    {
        Event::fake();

        $timer = $this->createTimer();
        $editedTimer = $this->getTimerArray();

        $response = $this->actingAs($this->user)->put('projects/'.$timer->project->id.'/timers/'.$timer->id, $editedTimer);

        $response->assertStatus(302);
        $response->assertRedirect('projects/'.$timer->project->id.'/timers');

        $updatedTimer = Timer::find($timer->id);

        $this->assertEquals($editedTimer['since_at'], $updatedTimer->since_at->format('Y-m-d H:i'));
        $this->assertEquals($editedTimer['until_at'], $updatedTimer->until_at->format('Y-m-d H:i'));
        $this->assertEquals($editedTimer['note'], $updatedTimer->note);
        $this->assertEquals($timer->project_id, $updatedTimer->project->id);
        $this->assertEquals($timer->user_id, $updatedTimer->user->id);
        $this->assertEquals($editedTimer['rate_id'], $updatedTimer->rate->id);

        Event::assertDispatched(TimerChanged::class);
    }

    public function test_user_can_start_and_stop_timer(): void
    {
        Event::fake();

        $project = $this->createProject(3);

        // Start timer
        $response = $this->actingAs($this->user)->post('projects/'.$project->id.'/timers/start', [
            'project_id' => $project->id,
            'rate_id' => Rate::factory()->create()->id,
        ]);

        $response->assertStatus(200);

        $startedTimer = Timer::latest()->first();

        $response->assertJsonPath('message', __('messages.timer.start'));
        $response->assertJsonPath('project.id', $project->id);

        $this->assertEquals($startedTimer->until_at, null);

        // Stop timer
        $response = $this->actingAs($this->user)->patch('projects/'.$startedTimer->project->id.'/timers/'.$startedTimer->id.'/stop');

        $response->assertStatus(200);

        $response->assertJsonPath('message', __('messages.timer.stop'));
        $response->assertJsonPath('timer.id', $startedTimer->id);

        $stoppedTimer = Timer::find($startedTimer->id);
        $this->assertNotEquals($stoppedTimer->until_at, null);

        Event::assertDispatched(TimerStopped::class);
    }

    public function test_user_can_delete_timer(): void
    {
        $timer = $this->createTimer();

        $response = $this->actingAs($this->user)->delete('projects/'.$timer->project->id.'/timers/'.$timer->id);

        $response->assertStatus(200);
        $response->assertJsonPath('message', __('messages.timer.delete'));

        $this->assertSoftDeleted($timer);
    }

    private function createUser(): User
    {
        return User::factory()->create([
            'address_id' => Address::factory(1)->create()->first()->id,
            'role_id' => RoleEnum::boss,
        ]);
    }

    private function createTimer($stopped = true): Timer
    {
        $project = Project::factory()->create([
            'client_id' => Client::factory()->create([
                'address_id' => Address::factory()->create()->first()->id,
                'social_network_id' => SocialNetwork::factory()->create()->first()->id,
            ])->id,
            'status' => ProjectStatusEnum::active->value,
        ]);

        $user = User::factory()->create([
            'address_id' => Address::factory(1)->create()->first()->id,
            'role_id' => RoleEnum::employee,
        ]);
        $project->team()->attach([$user->id]);

        return Timer::factory()->create([
            'project_id' => $project->id,
            'user_id' => $user->id,
            'rate_id' => Rate::factory()->create()->id,
        ]);
    }

    private function createProject(int $members = 0): Project
    {
        $project = Project::factory()->create([
            'client_id' => Client::factory()->create([
                'address_id' => Address::factory()->create()->first()->id,
                'social_network_id' => SocialNetwork::factory()->create()->first()->id,
            ])->id,
            'status' => ProjectStatusEnum::active->value,
        ]);
        $project->team()->attach(User::factory($members)->create([
            'address_id' => Address::factory(1)->create()->first()->id,
            'role_id' => RoleEnum::employee,
        ])->pluck('id'));

        return $project;
    }

    private function getTimerArray(): array
    {
        $project = Project::factory()->create([
            'client_id' => Client::factory()->create([
                'address_id' => Address::factory()->create()->first()->id,
                'social_network_id' => SocialNetwork::factory()->create()->first()->id,
            ])->id,
            'status' => ProjectStatusEnum::active->value,
        ]);

        $user = User::factory()->create([
            'address_id' => Address::factory(1)->create()->first()->id,
            'role_id' => RoleEnum::employee,
        ]);
        $project->team()->attach([$user->id]);

        return [
            'project_id' => $project->id,
            'user_id' => $user->id,
            'rate_id' => Rate::factory()->create()->id,
            'since_at' => '2023-07-13 12:20',
            'until_at' => '2023-07-13 13:00',
            'note' => 'Aenean sed pulvinar velit. Quisque suscipit, leo sit amet facilisis malesuada, neque eros lacinia sapien, eu gravida risus tellus ut enim.',
        ];
    }
}
