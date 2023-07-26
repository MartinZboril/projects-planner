<?php

namespace Tests\Feature;

use App\Enums\ProjectStatusEnum;
use App\Enums\RoleEnum;
use App\Events\Project\ProjectTeamChanged;
use App\Events\Project\Status\ProjectArchived;
use App\Events\Project\Status\ProjectFinished;
use App\Events\Project\Status\ProjectReactived;
use App\Models\Address;
use App\Models\Client;
use App\Models\Comment;
use App\Models\File;
use App\Models\Note;
use App\Models\Project;
use App\Models\SocialNetwork;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Event;
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

        Event::fake([ProjectTeamChanged::class]);
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

        Event::fake([ProjectTeamChanged::class]);
    }

    public function test_user_can_change_project_status(): void
    {
        Event::fake();

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

        Event::assertDispatched(ProjectFinished::class);

        // Activate
        $response = $this->actingAs($this->user)->patch('projects/'.$project->id.'/change-status', [
            'status' => ProjectStatusEnum::active->value,
        ]);

        $response->assertStatus(200);

        $response->assertJsonPath('message', __('messages.project.'.ProjectStatusEnum::active->name));
        $response->assertJsonPath('project.id', $project->id);
        $response->assertJsonPath('project.status', ProjectStatusEnum::active->value);

        Event::assertDispatched(ProjectReactived::class);

        // Archive
        $response = $this->actingAs($this->user)->patch('projects/'.$project->id.'/change-status', [
            'status' => ProjectStatusEnum::archive->value,
        ]);

        $response->assertStatus(200);

        $response->assertJsonPath('message', __('messages.project.'.ProjectStatusEnum::archive->name));
        $response->assertJsonPath('project.id', $project->id);
        $response->assertJsonPath('project.status', ProjectStatusEnum::archive->value);

        Event::assertDispatched(ProjectArchived::class);
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

    public function test_user_can_upload_file_for_project(): void
    {
        $project = $this->createProject(2);

        $projectFileName = 'project.jpg';

        $projectFiles = [
            'fileable_id' => $project->id,
            'fileable_type' => $project::class,
            'files' => [
                UploadedFile::fake()->image($projectFileName),
            ],
        ];

        $response = $this->actingAs($this->user)->post('projects/'.$project->id.'/files/upload', $projectFiles);

        $response->assertStatus(302);
        $response->assertRedirect('projects/'.$project->id.'/files');

        $lastProjectFile = File::where('fileable_id', $project->id)->where('fileable_type', $project::class)->latest()->first();
        $this->assertEquals($projectFiles['fileable_id'], $lastProjectFile->fileable_id);
        $this->assertEquals($projectFiles['fileable_type'], $lastProjectFile->fileable_type);
        $this->assertEquals($projectFileName, $lastProjectFile->file_name);
        $this->assertEquals('projects/files', $lastProjectFile->collection);
    }

    public function test_user_can_delete_file_for_project(): void
    {
        $project = $this->createProject(2);

        $projectFile = File::factory()->create([
            'fileable_id' => $project->id,
            'fileable_type' => $project::class,
        ]);

        $response = $this->actingAs($this->user)->delete('projects/'.$project->id.'/files/'.$projectFile->id);

        $response->assertStatus(200);
        $response->assertJsonPath('message', __('messages.file.delete'));

        $this->assertSoftDeleted($projectFile);
    }

    public function test_user_can_store_note_for_project(): void
    {
        $project = $this->createProject(1);

        $projectNote = [
            'user_id' => $this->user->id,
            'name' => 'Note',
            'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
            'noteable_id' => $project->id,
            'noteable_type' => $project::class,
        ];

        $response = $this->actingAs($this->user)->post('projects/'.$project->id.'/notes', $projectNote);

        $response->assertStatus(302);
        $response->assertRedirect('projects/'.$project->id.'/notes');

        $this->assertDatabaseHas('notes', $projectNote);

        $lastProjectNote = Note::where('noteable_id', $project->id)->where('noteable_type', $project::class)->latest()->first();
        $this->assertEquals($projectNote['noteable_id'], $lastProjectNote->noteable_id);
        $this->assertEquals($projectNote['noteable_type'], $lastProjectNote->noteable_type);
        $this->assertEquals($projectNote['content'], $lastProjectNote->content);
    }

    public function test_user_can_update_note_for_project(): void
    {
        $project = $this->createProject();

        $projectNote = Note::factory()->create([
            'user_id' => $this->user->id,
            'noteable_id' => $project->id,
            'noteable_type' => $project::class,
        ]);

        $editedProjectNote = [
            'name' => 'Updated Project Note',
            'content' => 'Updated Project Note Content',
        ];

        $response = $this->actingAs($this->user)->put('projects/'.$project->id.'/notes/'.$projectNote->id, $editedProjectNote);

        $response->assertStatus(302);
        $response->assertRedirect('projects/'.$project->id.'/notes');

        $lastProjectNote = Note::where('noteable_id', $project->id)->where('noteable_type', $project::class)->latest()->first();
        $this->assertEquals($editedProjectNote['content'], $lastProjectNote->content);
    }

    public function test_user_can_store_projects_comment(): void
    {
        $project = $this->createProject(1);

        $projectComment = [
            'commentable_id' => $project->id,
            'commentable_type' => $project::class,
            'user_id' => $this->user->id,
            'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
        ];

        $response = $this->actingAs($this->user)->post('projects/'.$project->id.'/comments', $projectComment);

        $response->assertStatus(302);
        $response->assertRedirect('projects/'.$project->id.'/comments');

        $this->assertDatabaseHas('comments', $projectComment);

        $lastProject = Comment::where('commentable_id', $project->id)->where('commentable_type', $project::class)->latest()->first();
        $this->assertEquals($projectComment['commentable_id'], $lastProject->commentable_id);
        $this->assertEquals($projectComment['commentable_type'], $lastProject->commentable_type);
        $this->assertEquals($projectComment['content'], $lastProject->content);
    }

    public function test_user_can_update_projects_comment(): void
    {
        $project = $this->createProject(1);

        $projectComment = Comment::factory()->create([
            'user_id' => $this->user->id,
            'commentable_id' => $project->id,
            'commentable_type' => $project::class,
        ]);

        $editedProjectComment = [
            'content' => 'Project Comment',
        ];

        $response = $this->actingAs($this->user)->put('projects/'.$project->id.'/comments/'.$projectComment->id, $editedProjectComment);

        $response->assertStatus(302);
        $response->assertRedirect('projects/'.$project->id.'/comments');

        $lastProjectComment = Comment::where('commentable_id', $project->id)->where('commentable_type', $project::class)->latest()->first();
        $this->assertEquals($editedProjectComment['content'], $lastProjectComment->content);
    }

    public function test_user_can_delete_projects_comment(): void
    {
        $project = $this->createProject(1);

        $projectComment = Comment::factory()->create([
            'user_id' => $this->user->id,
            'commentable_id' => $project->id,
            'commentable_type' => $project::class,
        ]);

        $response = $this->actingAs($this->user)->delete('projects/'.$project->id.'/comments/'.$projectComment->id);

        $response->assertStatus(200);
        $response->assertJsonPath('message', __('messages.comment.delete'));

        $this->assertSoftDeleted($projectComment);
    }

    public function test_user_can_upload_files_to_projects_comments(): void
    {
        $project = $this->createProject(1);

        $projectComment = Comment::factory()->create([
            'user_id' => $this->user->id,
            'commentable_id' => $project->id,
            'commentable_type' => $project::class,
        ]);

        [$projectCommentFile1, $projectCommentFile2] = ['project_comment_1.jpg', 'project_comment_2.jpg'];

        $editedProjectComment = [
            'content' => 'Updated comments with files',
            'files' => [
                UploadedFile::fake()->image($projectCommentFile1),
                UploadedFile::fake()->image($projectCommentFile2),
            ],
        ];

        $response = $this->actingAs($this->user)->put('projects/'.$project->id.'/comments/'.$projectComment->id, $editedProjectComment);

        $response->assertStatus(302);
        $response->assertRedirect('projects/'.$project->id.'/comments');

        $lastProjectComment = Comment::where('commentable_id', $project->id)->where('commentable_type', $project::class)->latest()->first();
        $lastProjectCommentFiles = File::where('fileable_id', $lastProjectComment->id)->where('fileable_type', $lastProjectComment::class)->latest()->get();
        $this->assertEquals(2, $lastProjectCommentFiles->count());
        $this->assertEquals($projectCommentFile1, $lastProjectCommentFiles[0]->file_name);
        $this->assertEquals('comments', $lastProjectCommentFiles[0]->collection);
        $this->assertEquals($projectCommentFile2, $lastProjectCommentFiles[1]->file_name);
        $this->assertEquals('comments', $lastProjectCommentFiles[1]->collection);
    }

    public function test_user_can_see_projects_reports(): void
    {
        $response = $this->actingAs($this->user)->get('report/projects');

        $response->assertStatus(200);
        $response->assertSee('Report for Projects');
    }

    public function test_user_can_see_projects_analysis(): void
    {
        $response = $this->actingAs($this->user)->get('analysis/projects');

        $response->assertStatus(200);
    }

    public function test_user_can_see_projects_dashboard(): void
    {
        $response = $this->actingAs($this->user)->get('dashboard/projects');

        $response->assertStatus(200);

        $response->assertSee('btn-primary mr-1">Projects<', false);
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
