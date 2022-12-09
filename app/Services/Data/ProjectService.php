<?php

namespace App\Services\Data;

use App\Enums\ProjectStatusEnum;
use App\Models\Project;
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
     * Store new project.
     */
    public function store(ValidatedInput $inputs): Project
    {
        $project = new Project;
        $project->status = ProjectStatusEnum::active;

        $project = $this->save($project, $inputs);

        foreach ($inputs->team as $userId) {
            $this->projectUserService->store($project->id, $userId);
        }

        return $project;
    }

    /**
     * Update project.
     */
    public function update(Project $project, ValidatedInput $inputs): Project
    {
        $project = $this->save($project, $inputs);
        $this->projectUserService->refresh($project->id);

        foreach ($inputs->team as $userId) {
            $this->projectUserService->store($project->id, $userId);
        }

        return $project;
    }

    /**
     * Save data for project.
     */
    protected function save(Project $project, ValidatedInput $inputs)
    {
        $project->client_id = $inputs->client_id;
        $project->name = $inputs->name;
        $project->start_date = $inputs->start_date;
        $project->due_date = $inputs->due_date;
        $project->estimated_hours = $inputs->estimated_hours;
        $project->budget = $inputs->budget;
        $project->description = $inputs->description;
        $project->save();

        return $project;
    }

    /**
     * Change working status of the project
     */
    public function change(Project $project, int $status): Project
    {
        $project->status = $status;
        $project->save();

        return $project;
    }

    /**
     * Get route for the action
     */
    public function redirect(string $action, Project $project): RedirectResponse 
    {
        switch ($action) {
            case 'projects':
                return redirect()->route('projects.index');
                break;
            case 'project':
                return redirect()->route('projects.detail', ['project' => $project->id]);
                break;
            default:
                return redirect()->back();
        }
    }
}