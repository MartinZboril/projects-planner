<?php

namespace App\Services\Data;

use Illuminate\Support\ValidatedInput;
use App\Models\{Comment, Milestone};
use App\Services\FileService;

class MilestoneService
{
    /**
     * Save data for milestone.
     */
    public function handleSave(Milestone $milestone, ValidatedInput $inputs): Milestone
    {
        return Milestone::updateOrCreate(
            ['id' => $milestone->id],
            [
                'project_id' => $milestone->project_id ?? $inputs->project_id,
                'owner_id' => $milestone->owner_id ?? $inputs->owner_id,
                'name' => $inputs->name,
                'start_date' => $inputs->start_date,
                'due_date' => $inputs->due_date,
                'colour' => $inputs->colour,
                'description' => $inputs->description,
            ]
        );
    }

    /**
     * Save milestones comments.
     */
    public function handleSaveComment(Milestone $milestone, Comment $comment): void
    {
        $milestone->comments()->save($comment);
    }
    
    /**
     * Upload milestones files.
     */
    public function handleUploadFiles(Milestone $milestone, Array $uploadedFiles): void
    {
        foreach ($uploadedFiles as $uploadedFile) {
            $milestone->files()->save((new FileService)->handleUpload($uploadedFile, 'milestones/files'));
        }
    }

    /**
     * Mark selected milestone.
     */
    public function handleMark(Milestone $milestone): Milestone
    {
        $milestone->is_marked = !$milestone->is_marked;
        $milestone->save();
        return $milestone;
    }
}