<?php

namespace App\Services\Data;

use App\Models\Project;
use Illuminate\Http\RedirectResponse;

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
    public function store(array $fields): Project
    {
        $project = new Project;
        $project->client_id = $fields['client_id'];
        $project->name = $fields['name'];
        $project->start_date = $fields['start_date'];
        $project->due_date = $fields['due_date'];
        $project->estimated_hours = $fields['estimated_hours'];
        $project->budget = $fields['budget'];
        $project->description = $fields['description'];
        $project->save();

        foreach ($fields['team'] as $userId) {
            $this->projectUserService->store($project->id, $userId);
        }

        return $project;
    }

    /**
     * Update project.
     */
    public function update(Project $project, array $fields): Project
    {
        Project::where('id', $project->id)
                    ->update([
                        'client_id' => $fields['client_id'],
                        'name' => $fields['name'],
                        'start_date' => $fields['start_date'],
                        'due_date' => $fields['due_date'],
                        'estimated_hours' => $fields['estimated_hours'],
                        'budget' => $fields['budget'],
                        'description' => $fields['description'],
                    ]);

        $project = Project::find($project->id);

        $this->projectUserService->refresh($project->id);

        foreach ($fields['team'] as $userId) {
            $this->projectUserService->store($project->id, $userId);
        }

        return $project;
    }

    /**
     * Change working status of the project
     */
    public function change(Project $project, array $fields): Project
    {
        Project::where('id', $project->id)
                    ->update([
                        'status' => $fields['status'],
                    ]);

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