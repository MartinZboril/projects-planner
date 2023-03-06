<?php

namespace App\Http\Controllers\Project\Milestone;

use Carbon\Carbon;
use Yajra\DataTables\DataTables;
use Illuminate\Http\{JsonResponse, Request};
use Illuminate\Support\Facades\Blade;
use App\Http\Controllers\Controller;
use App\Models\Milestone;

class LoadMilestoneController extends Controller
{
    /**
     * Load milestones to datatables.
     */
    public function __invoke(Request $request): JsonResponse
    {
        $overdue = $request->overdue;

        $data = Milestone::select('id', 'name', 'project_id', 'start_date', 'due_date', 'owner_id', 'is_marked')->get();

        return Datatables::of($data)->addIndexColumn()
            ->addColumn('detail', function($data) {
                return '<a href="' . route('projects.milestones.show', ['project' => $data->project, 'milestone' => $data]) . '">' . $data->name . '</a>';
            })
            ->addColumn('project', function($data) {
                return '<a href="' . route('projects.show', $data->project) . '">' . $data->project->name . '</a>';
            })
            ->addColumn('owner', function($data) {
                return Blade::render('<x-site.ui.user-icon :user="$owner" />', ['owner' => $data->owner]);
            })    
            ->addColumn('progress', function($data) {
                return Blade::render('<x-milestone.ui.progress :milestone="$milestone" />', ['milestone' => $data]);
            })    
            ->addColumn('buttons', function($data) {
                $buttons = '<a href="' . route('projects.milestones.edit', ['project' => $data->project, 'milestone' => $data]) . '" class="btn btn-xs btn-dark"><i class="fas fa-pencil-alt"></i></a> ';
                $buttons .= '<a href="' . route('projects.milestones.show', ['project' => $data->project, 'milestone' => $data]) . '" class="btn btn-xs btn-info"><i class="fas fa-eye"></i></a> ';
                $buttons .= view('projects.milestones.partials.buttons', ['milestone' => $data, 'buttonSize' => 'xs', 'hideButtonText' => '', 'type' => 'table']);
                return $buttons;
            })
            ->editColumn('start_date', function($data) {
                return Carbon::createFromFormat('Y-m-d H:i:s', $data->start_date)->format('d.m.Y');
            })
            ->editColumn('due_date', function($data) {
                return '<span class="text-' . ($data->overdue ? 'danger' : 'body') . '">' . Carbon::createFromFormat('Y-m-d H:i:s', $data->due_date)->format('d.m.Y') . '</span>';
            })
            ->rawColumns(['detail', 'project', 'progress', 'due_date', 'owner', 'buttons'])
            ->make(true);
    }
}