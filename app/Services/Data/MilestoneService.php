<?php

namespace App\Services\Data;

use App\Models\{Comment, Milestone};
use App\Services\FileService;

class MilestoneService
{
    /**
     * Save data for milestone.
     */
    public function handleSave(Milestone $milestone, array $inputs): Milestone
    {
        // Prepare fields
        $inputs['project_id'] = $milestone->project_id ?? $inputs['project_id'];
        $inputs['owner_id'] = $milestone->owner_id ?? $inputs['owner_id'];
        // Save note
        $milestone->fill($inputs)->save();
        return $milestone;
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
        $milestone->update(['is_marked' => !$milestone->is_marked]);
        return $milestone->fresh();
    }
}