<?php

namespace App\Services\Data;

use App\Models\{ProjectUser, Project, User};

class ProjectUserService
{
    /**
     * Store new project user.
     */
    public function storeUser(Project $project, User $user): void
    {
        ProjectUser::firstOrCreate(
            ['project_id' => $project->id, 'user_id' => $user->id],
            ['project_id' => $project->id, 'user_id' => $user->id]
        );
    }

    /**
     * Store new project users.
     */
    public function storeUsers(Project $project, array $userIds): void
    {
        ($project->team()->count() === 0) ? $project->team()->attach($userIds) : $project->team()->sync($userIds);
    }
}