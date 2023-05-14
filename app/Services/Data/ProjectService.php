<?php

namespace App\Services\Data;

use App\Enums\ProjectStatusEnum;
use App\Models\{Comment, Note, Project};
use App\Services\FileService;

class ProjectService
{
    /**
     * Save data for project.
     */
    public function handleSave(Project $project, array $inputs, ?Array $uploadedFiles=[])
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
    public function handleUploadFiles(Project $project, Array $uploadedFiles): void
    {
        foreach ($uploadedFiles as $uploadedFile) {
            $project->files()->save((new FileService)->handleUpload($uploadedFile, 'projects/files'));
        }
    }

    /**
     * Save projects comments.
     */
    public function handleSaveComment(Project $project, Comment $comment): void
    {
        $project->comments()->save($comment);
    }

    /**
     * Save projects notes.
     */
    public function handleSaveNote(Project $project, Note $note): void
    {
        $project->notes()->save($note);
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
        $project->update(['is_marked' => !$project->is_marked]);
        return $project->fresh();
    }
}