<?php

namespace App\Services\Data;

use App\Models\ProjectUser;

class ProjectUserService
{
    /**
     * Store new project user.
     */
    public function store(int $projectId, int $userId): void
    {
        $projectUser = new ProjectUser;
        $projectUser->project_id = $projectId;
        $projectUser->user_id = $userId;
        $projectUser->save();
    }

    /**
     * Refresh users from the project
     */
    public function refresh(int $projectId): void
    {
        ProjectUser::where('project_id', $projectId)->delete();
    }

    /**
     * Check if user is working on the project
     */
    public function workingOnProject(int $projectId, int $userId): bool
    {
        if(ProjectUser::where('project_id', $projectId)->where('user_id', $userId)->first()) {
            return true;
        }

        return false;
    }
}