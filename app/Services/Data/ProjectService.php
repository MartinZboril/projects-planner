<?php

namespace App\Services\Data;

use App\Enums\ProjectStatusEnum;
use App\Events\Project\ProjectTeamChanged;
use App\Events\Project\Status\ProjectArchived;
use App\Events\Project\Status\ProjectFinished;
use App\Events\Project\Status\ProjectReactived;
use App\Models\Project;
use App\Models\ProjectUser;
use App\Models\User;
use App\Services\FileService;
use Illuminate\Support\Collection;

class ProjectService
{
    public function __construct(
        private FileService $fileService,
    ) {
    }

    /**
     * Save data for project.
     */
    public function handleSave(Project $project, array $inputs, ?array $uploadedFiles = []): Project
    {
        $createdMode = $project->exists ? false : true;
        // Prepare fields
        $inputs['status'] = $project->status_id ?? ProjectStatusEnum::active;
        // Save note
        $project->fill($inputs)->save();
        // Upload files
        if ($uploadedFiles) {
            $this->handleUploadFiles($project, $uploadedFiles);
        }
        // Store projects team
        $currentUsersIds = ProjectUser::where('project_id', $project->id)->pluck('user_id');
        $oldTeam = $this->getSpecificMembers(User::whereIn('id', $currentUsersIds)->get(), $inputs['team']);
        ($project->team()->count() === 0) ? $project->team()->attach($inputs['team']) : $project->team()->sync($inputs['team']);
        $newTeam = $this->getSpecificMembers($project->team, $currentUsersIds->toArray());
        // Dispatch event with notify users about their assignmenents
        ProjectTeamChanged::dispatch($project, $newTeam, $oldTeam, $createdMode);

        return $project;
    }

    private function getSpecificMembers(Collection $team, array $users): Collection
    {
        $members = collect();

        foreach ($team as $user) {
            if (! in_array($user->id, $users)) {
                $members->push($user);
            }
        }

        return $members;
    }

    /**
     * Upload projects files.
     */
    public function handleUploadFiles(Project $project, array $uploadedFiles): void
    {
        foreach ($uploadedFiles as $uploadedFile) {
            $this->fileService->handleUpload($uploadedFile, 'projects/files', $project);
        }
    }

    /**
     * Change working status of the project.
     */
    public function handleChange(Project $project, int $status): Project
    {
        $project->update(['status' => $status]);

        switch ($project->status) {
            case ProjectStatusEnum::active:
                ProjectReactived::dispatch($project);
                break;

            case ProjectStatusEnum::finish:
                ProjectFinished::dispatch($project);
                break;

            case ProjectStatusEnum::archive:
                ProjectArchived::dispatch($project);
                break;
        }

        return $project->fresh();
    }

    /**
     * Mark selected project.
     */
    public function handleMark(Project $project): Project
    {
        $project->update(['is_marked' => ! $project->is_marked]);

        return $project->fresh();
    }

    /**
     * Delete selected project.
     */
    public function handleDelete(Project $project): void
    {
        $project->delete();
    }
}
