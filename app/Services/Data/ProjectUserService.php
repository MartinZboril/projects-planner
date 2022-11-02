<?php

namespace App\Services\Data;

use App\Models\ProjectUser;

class ProjectUserService
{
    public function store(int $projectId, int $userId): void
    {
        $projectUser = new ProjectUser;
        $projectUser->project_id = $projectId;
        $projectUser->user_id = $userId;
        $projectUser->save();
    }

    public function refresh(int $projectId): void
    {
        ProjectUser::where('project_id', $projectId)->delete();
    }

    public function workingOnProject(int $projectId, int $userId): bool
    {
        if(ProjectUser::where('project_id', $projectId)->where('user_id', $userId)->first()) {
            return true;
        }

        return false;
    }
}