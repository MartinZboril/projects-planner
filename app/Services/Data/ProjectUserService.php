<?php

namespace App\Services\Data;

use App\Models\{ProjectUser, Project, User};

class ProjectUserService
{
    /**
     * Store new project user.
     */
    public function handleStoreUser(Project $project, User $user): void
    {
        ProjectUser::firstOrCreate(
            ['project_id' => $project->id, 'user_id' => $user->id],
            ['project_id' => $project->id, 'user_id' => $user->id]
        );
    }
}