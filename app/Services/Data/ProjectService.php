<?php

namespace App\Services\Data;

use App\Enums\ProjectStatusEnum;
use App\Enums\Routes\ProjectRouteEnum;
use App\Models\Project;
use App\Services\RouteService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\ValidatedInput;

class ProjectService
{
    protected $projectUserService;

    public function __construct(ProjectUserService $projectUserService)
    {
        $this->projectUserService = $projectUserService;
    }

    /**
     * Save data for project.
     */
    public function save(Project $project, ValidatedInput $inputs)
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
     * Change working status of the project.
     */
    public function change(Project $project, int $status): void
    {
        $project->update(['status' => $status]);
    }
  
    /**
     * Mark selected project.
     */
    public function mark(Project $project): Project
    {
        $project->is_marked = !$project->is_marked;
        $project->save();
        return $project;
    }

    /**
     * Set up redirect for the action.
     */
    public function setUpRedirect(string $type, Project $project): RedirectResponse
    {
        $redirectAction = $type ? ProjectRouteEnum::Index : ProjectRouteEnum::Detail;
        $redirectVars = $type ? [] : ['project' => $project];
        return (new RouteService)->redirect($redirectAction->value, $redirectVars);
    }
}