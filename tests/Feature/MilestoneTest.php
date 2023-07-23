<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Client;
use App\Enums\RoleEnum;
use App\Models\Address;
use App\Models\Project;
use App\Models\SocialNetwork;
use App\Enums\ProjectStatusEnum;
use App\Models\Milestone;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MilestoneTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    public function setUp(): void
    {
        parent::setUp();

        $this->seed();

        $this->user = $this->createUser();
    }

    public function test_user_can_see_milestones_index_for_specific_project(): void
    {
        $project = $this->createProject(2);

        $response = $this->actingAs($this->user)->get('projects/'.$project->id.'/milestones');

        $response->assertStatus(200);
        $response->assertSee('Milestones');

        $this->assertDatabaseCount('milestones', 0);
    }

    public function test_user_can_see_projects_milestone_show(): void
    {
        $this->actingAs($this->user);

        $milestone = $this->createMilestone();

        // Show
        $response = $this->actingAs($this->user)->get('projects/'.$milestone->project->id.'/milestones/'.$milestone->id);
        $response->assertStatus(200);
        $response->assertSeeText($milestone->name);
    }

    public function test_user_can_get_to_create_projects_milestone_page(): void
    {
        $project = $this->createProject(2);

        $response = $this->actingAs($this->user)->get('projects/'.$project->id.'/milestones/create');

        $response->assertStatus(200);
        $response->assertSee('Create Milestone');
    }

    public function test_user_can_store_projects_milestone(): void
    {
        $milestone = $this->getMilestoneArray();

        $response = $this->actingAs($this->user)->post('projects/'.$milestone['project_id'].'/milestones', $milestone);

        $lastMilestone = Milestone::latest()->first();

        $response->assertStatus(302);
        $response->assertRedirect('projects/'.$lastMilestone->project->id.'/milestones/'.$lastMilestone->id);

        $this->assertDatabaseHas('milestones', [
            'id' => $lastMilestone->id,
        ]);

        $this->assertEquals($milestone['name'], $lastMilestone->name);
        $this->assertEquals($milestone['dued_at'], $lastMilestone->dued_at->format('Y-m-d'));
        $this->assertEquals($milestone['description'], $lastMilestone->description);
        $this->assertEquals($milestone['project_id'], $lastMilestone->project->id);
        $this->assertEquals($milestone['owner_id'], $lastMilestone->owner->id);

    }

    public function test_user_can_get_to_edit_projects_milestone_page(): void
    {
        $milestone = $this->createMilestone();

        $response = $this->actingAs($this->user)->get('projects/'.$milestone->project->id.'/milestones/'.$milestone->id.'/edit');

        $response->assertStatus(200);
        $response->assertSee('Edit Milestone');
        $response->assertSee('value="'.$milestone->name.'"', false);
        $response->assertSee('value="'.$milestone->started_at->format('Y-m-d').'"', false);
        $response->assertSee('>'.$milestone->description.'</textarea>', false);
        $response->assertViewHas('milestone', $milestone);
    }

    public function test_user_can_update_projects_milestone(): void
    {
        $milestone = $this->createMilestone();
        $editedMilestone = $this->getMilestoneArray();

        $response = $this->actingAs($this->user)->put('projects/'.$milestone->project->id.'/milestones/'.$milestone->id, $editedMilestone);

        $response->assertStatus(302);
        $response->assertRedirect('projects/'.$milestone->project->id.'/milestones/'.$milestone->id);

        $updatedMilestone = Milestone::find($milestone->id);

        $this->assertEquals($editedMilestone['name'], $updatedMilestone->name);
        $this->assertEquals($editedMilestone['description'], $updatedMilestone->description);
        $this->assertEquals($milestone->id, $updatedMilestone->project->id);
        $this->assertEquals($editedMilestone['owner_id'], $updatedMilestone->owner->id);
    }

    public function test_user_can_mark_projects_milestone(): void
    {
        $milestone = $this->createMilestone();

        // Mark milestone
        $response = $this->actingAs($this->user)->patch('projects/'.$milestone->project->id.'/milestones/'.$milestone->id.'/mark');

        $response->assertStatus(200);

        $response->assertJsonPath('message', __('messages.milestone.mark'));
        $response->assertJsonPath('milestone.id', $milestone->id);

        $markedMilestone = Milestone::find($milestone->id);
        $this->assertEquals($markedMilestone->is_marked, true);

        // Unmark milestone
        $response = $this->actingAs($this->user)->patch('projects/'.$milestone->project->id.'/milestones/'.$milestone->id.'/mark');

        $response->assertStatus(200);

        $response->assertJsonPath('message', __('messages.milestone.unmark'));
        $response->assertJsonPath('milestone.id', $milestone->id);

        $markedMilestone = Project::find($milestone->id);
        $this->assertEquals($markedMilestone->is_marked, false);
    }

    public function test_user_can_delete_projects_milestone(): void
    {
        $milestone = $this->createMilestone();

        $response = $this->actingAs($this->user)->delete('projects/'.$milestone->project->id.'/milestones/'.$milestone->id);

        $response->assertStatus(200);
        $response->assertJsonPath('message', __('messages.milestone.delete'));

        $this->assertSoftDeleted($milestone);
    }

    private function createUser(): User
    {
        return User::factory()->create([
            'address_id' => Address::factory(1)->create()->first()->id,
            'role_id' => RoleEnum::boss,
        ]);
    }

    private function createMilestone(): Milestone
    {
        $this->actingAs($this->user);

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

        return Milestone::factory()->create([
            'project_id' => $project->id,
            'owner_id' => $ownerId,
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

    private function getMilestoneArray(): array
    {
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

        return [
            'project_id' => $project->id,
            'owner_id' => $ownerId,
            'name' => 'Milestone',
            'colour' => '#000000',
            'started_at' => '2023-07-10',
            'dued_at' => '2023-07-13',
            'description' => 'Aenean sed pulvinar velit. Quisque suscipit, leo sit amet facilisis malesuada, neque eros lacinia sapien, eu gravida risus tellus ut enim.',
        ];
    }
}
