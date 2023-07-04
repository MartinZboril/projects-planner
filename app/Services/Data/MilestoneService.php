<?php

namespace App\Services\Data;

use App\Models\Milestone;
use App\Notifications\Milestone\OwnerAssignedNotification;
use App\Services\FileService;

class MilestoneService
{
    public function __construct(
        private FileService $fileService,
    ) {
    }

    /**
     * Save data for milestone.
     */
    public function handleSave(Milestone $milestone, array $inputs, ?array $uploadedFiles = []): Milestone
    {
        $oldOwnerId = $milestone->owner_id;
        // Prepare fields
        $inputs['project_id'] = $milestone->project_id ?? $inputs['project_id'];
        $inputs['owner_id'] = $inputs['owner_id'] ?? $milestone->owner_id;
        // Save note
        $milestone->fill($inputs)->save();
        // Upload files
        if ($uploadedFiles) {
            $this->handleUploadFiles($milestone, $uploadedFiles);
        }
        // Notify owner about assigning to the milestone
        if (($milestone->owner ?? false) && ((int) $oldOwnerId !== (int) $milestone->owner_id)) {
            $milestone->owner->notify(new OwnerAssignedNotification($milestone));
        }

        return $milestone;
    }

    /**
     * Upload milestones files.
     */
    public function handleUploadFiles(Milestone $milestone, array $uploadedFiles): void
    {
        foreach ($uploadedFiles as $uploadedFile) {
            $this->fileService->handleUpload($uploadedFile, 'milestones/files', $milestone);
        }
    }

    /**
     * Mark selected milestone.
     */
    public function handleMark(Milestone $milestone): Milestone
    {
        $milestone->update(['is_marked' => ! $milestone->is_marked]);

        return $milestone->fresh();
    }

    /**
     * Delete selected milestone.
     */
    public function handleDelete(Milestone $milestone): void
    {
        $milestone->delete();
    }
}
