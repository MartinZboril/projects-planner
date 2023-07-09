<?php

namespace App\Services\Data;

use App\Enums\ProjectStatusEnum;
use App\Events\Project\ProjectTeamChanged;
use App\Models\Project;
use App\Models\ProjectUser;
use App\Models\User;
use App\Notifications\Project\Status\ArchivedProjectNotification;
use App\Notifications\Project\Status\FinishedProjectNotification;
use App\Notifications\Project\Status\ReactivedProjectNotification;
use App\Services\FileService;

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
        // Prepare fields
        $inputs['status'] = $project->status_id ?? ProjectStatusEnum::active;
        // Save note
        $project->fill($inputs)->save();
        // Upload files
        if ($uploadedFiles) {
            $this->handleUploadFiles($project, $uploadedFiles);
        }
        // Store projects team
        $oldTeam = ProjectUser::where('project_id', $project->id)->pluck('user_id');
        ($project->team()->count() === 0) ? $project->team()->attach($inputs['team']) : $project->team()->sync($inputs['team']);
        $newTeam = ProjectUser::where('project_id', $project->id)->pluck('user_id');
        // Dispatch event with notify users about their assignmenents
        ProjectTeamChanged::dispatch($project, $newTeam, $oldTeam);

        return $project;
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

        $notification = null;

        switch ($project->status) {
            case ProjectStatusEnum::active:
                $notification = new ReactivedProjectNotification($project);
                break;

            case ProjectStatusEnum::finish:
                $notification = new FinishedProjectNotification($project);
                break;

            case ProjectStatusEnum::archive:
                $notification = new ArchivedProjectNotification($project);
                break;
        }

        if ($notification) {
            $project->team->each(function (User $user) use ($notification) {
                $user->notify($notification);
            });
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
