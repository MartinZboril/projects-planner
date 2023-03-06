<?php

namespace App\Http\Controllers\Task;

use Carbon\Carbon;
use Yajra\DataTables\DataTables;
use Illuminate\Http\{JsonResponse, Request};
use Illuminate\Support\Facades\Blade;
use App\Http\Controllers\Controller;
use App\Models\Task;

class LoadTaskController extends Controller
{
    /**
     * Load tasks to datatables.
     */
    public function __invoke(Request $request): JsonResponse
    {
        $projectId = $request->project_id;
        $milestoneId = $request->milestone_id;
        $type = $request->type;
        $overdue = $request->overdue;
        $status = $request->status;

        $data = Task::select('id', 'name', 'user_id', 'project_id', 'status', 'due_date', 'is_stopped', 'is_returned', 'is_marked')->when(
            $projectId ?? false,
            fn ($query, $value) => $query->where('project_id', $value)
        )->when(
            $milestoneId ?? false,
            fn ($query, $value) => $query->where('milestone_id', $value)
        )->when(
            $overdue ?? false,
            fn ($query, $value) => $query->overdue()->active()
        )->when(
            $status ?? false,
            fn ($query, $value) => $query->where('status', $value)
        )->get();

        return Datatables::of($data)->addIndexColumn()
            ->addColumn('detail', function($data) use($type) {
                return '<a href="' . ($type === 'projects' ? route('projects.tasks.show', ['project' => $data->project, 'task' => $data]) : route('tasks.show', $data)) . '">' . $data->name . '</a>';
            })
            ->addColumn('project', function($data) {
                return '<a href="' . route('projects.show', $data->project) . '">' . $data->project->name . '</a>';
            })
            ->addColumn('milestone', function($data) {
                if ($task = Task::where('id', $data->id)->whereNotNull('milestone_id')->select('project_id', 'milestone_id')->first()) {
                    return '<a href="' . route('projects.milestones.show', ['project' => $task->project, 'milestone' => $task->milestone]) . '">' . $task->milestone->name . '</a>';                
                }
                return 'NaN';
            })
            ->addColumn('user', function($data) {
                return Blade::render('<x-site.ui.user-icon :user="$user" />', ['user' => $data->user]);
            })
            ->addColumn('buttons', function($data) use($type) {
                $buttons = '<a href="' . ($type === 'projects' ? route('projects.tasks.edit', ['project' => $data->project, 'task' => $data]) : route('tasks.edit', $data)) . '" class="btn btn-xs btn-dark"><i class="fas fa-pencil-alt"></i></a> ';
                $buttons .= '<a href="' . ($type === 'projects' ? route('projects.tasks.show', ['project' => $data->project, 'task' => $data]) : route('tasks.show', $data)) . '" class="btn btn-xs btn-info"><i class="fas fa-eye"></i></a> ';
                $buttons .= view('tasks.partials.buttons', ['task' => $data, 'buttonSize' => 'xs', 'hideButtonText' => '', 'type' => 'table']);
                return $buttons;
            })
            ->editColumn('due_date', function($data) {
                return '<span class="text-' . ($data->overdue ? 'danger' : 'body') . '">' . Carbon::createFromFormat('Y-m-d H:i:s', $data->due_date)->format('d.m.Y') . '</span>';
            })
            ->addColumn('status_badge', function($data) {
                return Blade::render('<x-task.ui.status-badge :text="true" :task="$task" />', ['task' => $data]);
            })
            ->rawColumns(['detail', 'project', 'milestone', 'due_date', 'user', 'buttons'])
            ->make(true);
    }
}