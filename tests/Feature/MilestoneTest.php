<?php

namespace Tests\Feature;

use App\Enums\ProjectStatusEnum;
use App\Enums\RoleEnum;
use App\Models\Address;
use App\Models\Client;
use App\Models\Comment;
use App\Models\File;
use App\Models\Milestone;
use App\Models\Project;
use App\Models\SocialNetwork;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

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

    public function test_user_can_upload_file_for_milestone(): void
    {
        $milestone = $this->createMilestone();

        $milestoneFileName = 'milestone.jpg';

        $milestoneFiles = [
            'fileable_id' => $milestone->id,
            'fileable_type' => $milestone::class,
            'files' => [
                UploadedFile::fake()->image($milestoneFileName),
            ],
        ];

        $response = $this->actingAs($this->user)->post('projects/'.$milestone->project->id.'/milestones/'.$milestone->id.'/files/upload', $milestoneFiles);

        $response->assertStatus(302);
        $response->assertRedirect('projects/'.$milestone->project->id.'/milestones/'.$milestone->id);

        $lastMilestoneFile = File::where('fileable_id', $milestone->id)->where('fileable_type', $milestone::class)->latest()->first();
        $this->assertEquals($milestoneFiles['fileable_id'], $lastMilestoneFile->fileable_id);
        $this->assertEquals($milestoneFiles['fileable_type'], $lastMilestoneFile->fileable_type);
        $this->assertEquals($milestoneFileName, $lastMilestoneFile->file_name);
        $this->assertEquals('milestones/files', $lastMilestoneFile->collection);
    }

    public function test_user_can_delete_file_for_milestone(): void
    {
        $milestone = $this->createMilestone();

        $milestoneFile = File::factory()->create([
            'fileable_id' => $milestone->id,
            'fileable_type' => $milestone::class,
        ]);

        $response = $this->actingAs($this->user)->delete('projects/'.$milestone->project->id.'/milestones/'.$milestone->id.'/files/'.$milestoneFile->id);

        $response->assertStatus(200);
        $response->assertJsonPath('message', __('messages.file.delete'));

        $this->assertSoftDeleted($milestoneFile);
    }

    public function test_user_can_store_milestones_comment(): void
    {
        $milestone = $this->createMilestone();

        $milestoneComment = [
            'commentable_id' => $milestone->id,
            'commentable_type' => $milestone::class,
            'user_id' => $this->user->id,
            'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
        ];

        $response = $this->actingAs($this->user)->post('projects/'.$milestone->project->id.'/milestones/'.$milestone->id.'/comments', $milestoneComment);

        $response->assertStatus(302);
        $response->assertRedirect('projects/'.$milestone->project->id.'/milestones/'.$milestone->id);

        $this->assertDatabaseHas('comments', $milestoneComment);

        $lastMilestone = Comment::where('commentable_id', $milestone->id)->where('commentable_type', $milestone::class)->latest()->first();
        $this->assertEquals($milestoneComment['commentable_id'], $lastMilestone->commentable_id);
        $this->assertEquals($milestoneComment['commentable_type'], $lastMilestone->commentable_type);
        $this->assertEquals($milestoneComment['content'], $lastMilestone->content);
    }

    public function test_user_can_update_milestones_comment(): void
    {
        $milestone = $this->createMilestone();

        $milestoneComment = Comment::factory()->create([
            'user_id' => $this->user->id,
            'commentable_id' => $milestone->id,
            'commentable_type' => $milestone::class,
        ]);

        $editedMilestoneComment = [
            'content' => 'Milestone Comment',
        ];

        $response = $this->actingAs($this->user)->put('projects/'.$milestone->project->id.'/milestones/'.$milestone->id.'/comments/'.$milestoneComment->id, $editedMilestoneComment);

        $response->assertStatus(302);
        $response->assertRedirect('projects/'.$milestone->project->id.'/milestones/'.$milestone->id);

        $lastMilestoneComment = Comment::where('commentable_id', $milestone->id)->where('commentable_type', $milestone::class)->latest()->first();
        $this->assertEquals($editedMilestoneComment['content'], $lastMilestoneComment->content);
    }

    public function test_user_can_delete_milestones_comment(): void
    {
        $milestone = $this->createMilestone();

        $milestoneComment = Comment::factory()->create([
            'user_id' => $this->user->id,
            'commentable_id' => $milestone->id,
            'commentable_type' => $milestone::class,
        ]);

        $response = $this->actingAs($this->user)->delete('projects/'.$milestone->project->id.'/milestones/'.$milestone->id.'/comments/'.$milestoneComment->id);

        $response->assertStatus(200);
        $response->assertJsonPath('message', __('messages.comment.delete'));

        $this->assertSoftDeleted($milestoneComment);
    }

    public function test_user_can_upload_files_to_milestones_comment(): void
    {
        $milestone = $this->createMilestone();

        $milestoneComment = Comment::factory()->create([
            'user_id' => $this->user->id,
            'commentable_id' => $milestone->id,
            'commentable_type' => $milestone::class,
        ]);

        [$milestoneCommentFile1, $milestoneCommentFile2] = ['milestone_comment_1.jpg', 'milestone_comment_2.jpg'];

        $editedMilestoneComment = [
            'content' => 'Updated comments with files',
            'files' => [
                UploadedFile::fake()->image($milestoneCommentFile1),
                UploadedFile::fake()->image($milestoneCommentFile2),
            ],
        ];

        $response = $this->actingAs($this->user)->put('projects/'.$milestone->project->id.'/milestones/'.$milestone->id.'/comments/'.$milestoneComment->id, $editedMilestoneComment);

        $response->assertStatus(302);
        $response->assertRedirect('projects/'.$milestone->project->id.'/milestones/'.$milestone->id);

        $lastMilestoneComment = Comment::where('commentable_id', $milestone->id)->where('commentable_type', $milestone::class)->latest()->first();
        $lastMilestoneCommentFiles = File::where('fileable_id', $lastMilestoneComment->id)->where('fileable_type', $lastMilestoneComment::class)->latest()->get();
        $this->assertEquals(2, $lastMilestoneCommentFiles->count());
        $this->assertEquals($milestoneCommentFile1, $lastMilestoneCommentFiles[0]->file_name);
        $this->assertEquals('comments', $lastMilestoneCommentFiles[0]->collection);
        $this->assertEquals($milestoneCommentFile2, $lastMilestoneCommentFiles[1]->file_name);
        $this->assertEquals('comments', $lastMilestoneCommentFiles[1]->collection);
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
