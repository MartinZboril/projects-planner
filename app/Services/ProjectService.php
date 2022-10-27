<?php

namespace App\Services;

use App\Models\Project;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ProjectService
{
    protected $projectUserService;

    public function __construct(ProjectUserService $projectUserService)
    {
        $this->projectUserService = $projectUserService;
    }

    public function store(Request $request): Project
    {
        $project = new Project;
        $project->name = $request->name;
        $project->client_id = $request->client_id;
        $project->start_date = $request->start_date;
        $project->due_date = $request->due_date;
        $project->estimated_hours = $request->estimated_hours;
        $project->budget = $request->budget;
        $project->description = $request->description;
        $project->save();

        foreach ($request->team as $userId) {
            $this->projectUserService->store($project->id, $userId);
        }

        return $project;
    }

    public function update(Project $project, Request $request): Project
    {
        Project::where('id', $project->id)
                    ->update([
                        'name' => $request->name,
                        'client_id' => $request->client_id,
                        'start_date' => $request->start_date,
                        'due_date' => $request->due_date,
                        'estimated_hours' => $request->estimated_hours,
                        'budget' => $request->budget,
                        'description' => $request->description,
                    ]);

        $project = Project::find($project->id);

        $this->projectUserService->refresh($project->id);

        foreach ($request->team as $userId) {
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