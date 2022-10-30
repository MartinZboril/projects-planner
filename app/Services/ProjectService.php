<?php

namespace App\Services;

use App\Models\Project;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Session;

class ProjectService
{
    protected $projectUserService;

    public function __construct(ProjectUserService $projectUserService)
    {
        $this->projectUserService = $projectUserService;
    }

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
    
    public function flash(string $action): void
    {
        switch ($action) {
            case 'create':
                Session::flash('message', __('messages.project.create'));
                Session::flash('type', 'info');
                break;
            case 'update':
                Session::flash('message', __('messages.project.update'));
                Session::flash('type', 'info');
                break;
            default:
                Session::flash('message', __('messages.complete'));
                Session::flash('type', 'info');
        }
    }

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