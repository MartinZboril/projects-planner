<?php

namespace App\Services\Data;

use Illuminate\Support\ValidatedInput;
use App\Enums\ProjectStatusEnum;
use App\Models\{Comment, Note, Project, ProjectComment, ProjectFile, ProjectNote};
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
                'project_id' => $inputs->project_id,
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
            ProjectFile::create([
                'project_id' => $project->id,
                'file_id' => (new FileService)->handleUpload($uploadedFile, 'projects/files')->id
            ]);
        }
    }

    /**
     * Save projects comments.
     */
    public function handleSaveComment(Project $project, Comment $comment): void
    {
        ProjectComment::create([
            'project_id' => $project->id,
            'comment_id' => $comment->id
        ]);
    }

    /**
     * Save projects notes.
     */
    public function handleSaveNote(Project $project, Note $note): void
    {
        ProjectNote::create([
            'project_id' => $project->id,
            'note_id' => $note->id
        ]);
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