<?php

namespace App\Http\Controllers\Project;

use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use App\Http\Controllers\Controller;
use App\Http\Requests\Project\{StoreProjectRequest, UpdateProjectRequest};
use App\Models\{Client, Project, User};
use App\Traits\FlashTrait;
use App\Services\Data\ProjectService;

class ProjectController extends Controller
{
    use FlashTrait;

    public function __construct(private ProjectService $projectService)
    {        
    }

    /**
     * Display a listing of the projects.
     */
    public function index(): View
    {
        return view('projects.index', ['projects' => Project::all()]);
    }

    /**
     * Show the form for creating a new project.
     */
    public function create(): View
    {
        return view('projects.create', ['project' => new Project, 'clients' => Client::all(), 'users' => User::all()]);
    }

    /**
     * Store a newly created project in storage.
     */
    public function store(StoreProjectRequest $request): RedirectResponse
    {
        try {
            $project = $this->projectService->handleSave(new Project, $request->safe());
            $this->flash(__('messages.project.create'), 'info');
        } catch (Exception $exception) {
            Log::error($exception);
            return redirect()->back()->with(['error' => __('messages.error')]);
        }
        return $request->has('save_and_close')
            ? redirect()->route('projects.index')
            : redirect()->route('projects.show', $project);
    }

    /**
     * Display the project.
     */
    public function show(Project $project): View
    {
        return view('projects.show', ['project' => $project]);
    }

    /**
     * Show the form for editing the project.
     */
    public function edit(Project $project): View
    {
        return view('projects.edit', ['project' => $project, 'clients' => Client::all(), 'users' => User::all()]);
    }

    /**
     * Update the project in storage.
     */
    public function update(UpdateProjectRequest $request, Project $project): RedirectResponse
    {
        try {
            $project = $this->projectService->handleSave($project, $request->safe());
            $this->flash(__('messages.project.update'), 'info');
        } catch (Exception $exception) {
            Log::error($exception);
            return redirect()->back()->with(['error' => __('messages.error')]);
        }
        return $request->has('save_and_close')
            ? redirect()->route('projects.index')
            : redirect()->route('projects.show', $project);
    }
}
