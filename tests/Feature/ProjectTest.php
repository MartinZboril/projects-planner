<?php

namespace Tests\Feature;

use App\Enums\ProjectStatusEnum;
use App\Enums\RoleEnum;
use App\Models\Address;
use App\Models\Client;
use App\Models\Project;
use App\Models\SocialNetwork;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProjectTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    public function setUp(): void
    {
        parent::setUp();

        $this->seed();

        $this->user = $this->createUser();
    }

    public function test_user_can_see_projects_index(): void
    {
        $response = $this->actingAs($this->user)->get('projects');

        $response->assertStatus(200);
        $response->assertSee('Projects');

        $this->assertDatabaseCount('projects', 0);
    }

    public function test_user_can_see_projects_show(): void
    {
        $this->actingAs($this->user);

        $project = $this->createProject(3);

        // Show
        $response = $this->actingAs($this->user)->get('projects/'.$project->id);
        $response->assertStatus(200);
        $response->assertSeeText($project->name);

        // Tasks
        $response = $this->actingAs($this->user)->get('projects/'.$project->id.'/tasks');
        $response->assertStatus(200);

        // Kanban
        $response = $this->actingAs($this->user)->get('projects/'.$project->id.'/tasks/kanban');
        $response->assertStatus(200);

        // Milestones
        $response = $this->actingAs($this->user)->get('projects/'.$project->id.'/milestones');
        $response->assertStatus(200);

        // Timesheets
        $response = $this->actingAs($this->user)->get('projects/'.$project->id.'/timers');
        $response->assertStatus(200);

        // Tickets
        $response = $this->actingAs($this->user)->get('projects/'.$project->id.'/tickets');
        $response->assertStatus(200);

        // Comments
        $response = $this->actingAs($this->user)->get('projects/'.$project->id.'/comments');
        $response->assertStatus(200);

        // Files
        $response = $this->actingAs($this->user)->get('projects/'.$project->id.'/files');
        $response->assertStatus(200);

        // Notes
        $response = $this->actingAs($this->user)->get('projects/'.$project->id.'/notes');
        $response->assertStatus(200);
    }

    public function test_user_can_get_to_create_project_page(): void
    {
        $response = $this->actingAs($this->user)->get('projects/create');

        $response->assertStatus(200);
        $response->assertSee('Create Project');
    }

    public function test_user_can_store_project(): void
    {
        $project = $this->getProjectArray();
        $project['client_id'] = Client::factory()->create([
            'address_id' => Address::factory()->create()->first()->id,
            'social_network_id' => SocialNetwork::factory()->create()->first()->id,
        ])->id;
        $project['team'] = User::factory(3)->create([
            'address_id' => Address::factory(1)->create()->first()->id,
            'role_id' => RoleEnum::employee,
        ])->pluck('id')->toArray();

        $response = $this->actingAs($this->user)->post('projects', $project);

        $lastProject = Project::latest()->first();

        $response->assertStatus(302);
        $response->assertRedirect('projects/'.$lastProject->id);

        $this->assertDatabaseHas('projects', [
            'id' => $lastProject->id,
        ]);

        $this->assertEquals($project['name'], $lastProject->name);
        $this->assertEquals($project['started_at'], $lastProject->started_at->format('Y-m-d'));
        $this->assertEquals($project['description'], $lastProject->description);
        $this->assertEquals(count($project['team']), $lastProject->team->count());
        $this->assertEquals(ProjectStatusEnum::active, $lastProject->status);
    }

    public function test_user_can_get_to_edit_project_page(): void
    {
        $project = $this->createProject(3);

        $response = $this->actingAs($this->user)->get('projects/'.$project->id.'/edit');

        $response->assertStatus(200);
        $response->assertSee('Edit Project');
        $response->assertSee('value="'.$project->name.'"', false);
        $response->assertSee('value="'.$project->started_at->format('Y-m-d').'"', false);
        $response->assertSee('>'.$project->description.'</textarea>', false);
        $response->assertViewHas('project', $project);
    }

    public function test_user_can_update_project(): void
    {
        $project = $this->createProject(2);
        $editedProject = $this->getProjectArray();
        $editedProject['client_id'] = Client::factory()->create([
            'address_id' => Address::factory()->create()->first()->id,
            'social_network_id' => SocialNetwork::factory()->create()->first()->id,
        ])->id;
        $editedProject['team'] = User::factory(3)->create([
            'address_id' => Address::factory(1)->create()->first()->id,
            'role_id' => RoleEnum::employee,
        ])->pluck('id')->toArray();

        $response = $this->actingAs($this->user)->put('projects/'.$project->id, $editedProject);

        $response->assertStatus(302);
        $response->assertRedirect('projects/'.$project->id);

        $updatedProject = Project::find($project->id);

        $this->assertEquals($editedProject['name'], $updatedProject->name);
        $this->assertEquals($editedProject['description'], $updatedProject->description);
        $this->assertEquals(count($project['team']), $updatedProject->team->count());
    }

    public function test_user_can_change_project_status(): void
    {
        $project = $this->createProject(3);

        $this->assertEquals(ProjectStatusEnum::active->value, $project->status->value);

        // Finish
        $response = $this->actingAs($this->user)->patch('projects/'.$project->id.'/change-status', [
            'status' => ProjectStatusEnum::finish->value,
        ]);

        $response->assertStatus(200);

        $response->assertJsonPath('message', __('messages.project.'.ProjectStatusEnum::finish->name));
        $response->assertJsonPath('project.id', $project->id);
        $response->assertJsonPath('project.status', ProjectStatusEnum::finish->value);

        // Activate
        $response = $this->actingAs($this->user)->patch('projects/'.$project->id.'/change-status', [
            'status' => ProjectStatusEnum::active->value,
        ]);

        $response->assertStatus(200);

        $response->assertJsonPath('message', __('messages.project.'.ProjectStatusEnum::active->name));
        $response->assertJsonPath('project.id', $project->id);
        $response->assertJsonPath('project.status', ProjectStatusEnum::active->value);

        // Archive
        $response = $this->actingAs($this->user)->patch('projects/'.$project->id.'/change-status', [
            'status' => ProjectStatusEnum::archive->value,
        ]);

        $response->assertStatus(200);

        $response->assertJsonPath('message', __('messages.project.'.ProjectStatusEnum::archive->name));
        $response->assertJsonPath('project.id', $project->id);
        $response->assertJsonPath('project.status', ProjectStatusEnum::archive->value);
    }

    public function test_user_can_mark_project(): void
    {
        $project = $this->createProject(3);

        // Mark project
        $response = $this->actingAs($this->user)->patch('projects/'.$project->id.'/mark');

        $response->assertStatus(200);

        $response->assertJsonPath('message', __('messages.project.mark'));
        $response->assertJsonPath('project.id', $project->id);

        $markedProject = Project::find($project->id);
        $this->assertEquals($markedProject->is_marked, true);

        // Unmark project
        $response = $this->actingAs($this->user)->patch('projects/'.$project->id.'/mark');

        $response->assertStatus(200);

        $response->assertJsonPath('message', __('messages.project.unmark'));
        $response->assertJsonPath('project.id', $project->id);

        $markedProject = Project::find($project->id);
        $this->assertEquals($markedProject->is_marked, false);
    }

    public function test_user_can_delete_project(): void
    {
        $project = $this->createProject(2);

        $response = $this->actingAs($this->user)->delete('projects/'.$project->id);

        $response->assertStatus(200);
        $response->assertJsonPath('message', __('messages.project.delete'));

        $this->assertSoftDeleted($project);
    }

    private function createUser(): User
    {
        return User::factory()->create([
            'address_id' => Address::factory(1)->create()->first()->id,
            'role_id' => RoleEnum::boss,
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

    private function getProjectArray(): array
    {
        return [
            'name' => 'Project',
            'started_at' => '2023-07-01',
            'dued_at' => '2023-07-10',
            'estimated_hours' => 100,
            'budget' => 10000,
            'description' => 'Aenean sed pulvinar velit. Quisque suscipit, leo sit amet facilisis malesuada, neque eros lacinia sapien, eu gravida risus tellus ut enim.',
        ];
    }
}
