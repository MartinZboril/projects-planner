<?php

namespace App\Services\Data;

use Illuminate\Support\ValidatedInput;
use App\Enums\ProjectStatusEnum;
use App\Models\{Comment, Note, Project};
use App\Services\FileService;

class ProjectService
{
    public function __construct(private ProjectUserService $projectUserService)
    {
    }

    /**
     * Save data for project.
     */
    public function handleSave(Project $project, ValidatedInput $inputs)
    {
        $project = Project::updateOrCreate(
            ['id' => $project->id],
            [
                'status' => $project->status_id ?? ProjectStatusEnum::active,
                'client_id' => $inputs->client_id,
                'name' => $inputs->name,
                'start_date' => $inputs->start_date,
                'due_date' => $inputs->due_date,
                'estimated_hours' => $inputs->estimated_hours,
                'budget' => $inputs->budget,
                'description' => $inputs->description,
            ]
        );

        $this->projectUserService->handleStoreUsers($project, $inputs->team);

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
    public function handleChange(Project $project, int $status): void
    {
        $project->update(['status' => $status]);
    }
  
    /**
     * Mark selected project.
     */
    public function handleMark(Project $project): Project
    {
        $project->is_marked = !$project->is_marked;
        $project->save();
        return $project;
    }
}