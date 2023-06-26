<?php

namespace App\Services\Data;

use App\Enums\ProjectStatusEnum;
use App\Models\Note;
use App\Models\Project;
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
        ($project->team()->count() === 0) ? $project->team()->attach($inputs['team']) : $project->team()->sync($inputs['team']);

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
