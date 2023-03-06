<?php

namespace App\Http\Controllers\Project;

use Carbon\Carbon;
use Yajra\DataTables\DataTables;
use Illuminate\Http\{JsonResponse, Request};
use Illuminate\Support\Facades\Blade;
use App\Http\Controllers\Controller;
use App\Models\Project;

class LoadProjectController extends Controller
{
    /**
     * Load projects to datatables.
     */
    public function __invoke(Request $request): JsonResponse
    {
        $overdue = $request->overdue;

        $data = Project::select('id', 'name', 'client_id', 'status', 'due_date', 'estimated_hours', 'budget', 'is_marked')->when(
            $overdue ?? false,
            fn ($query, $value) => $query->overdue()->active()
        )->get();

        return Datatables::of($data)->addIndexColumn()
            ->addColumn('detail', function($data) {
                return '<a href="' . route('projects.show', $data) . '">' . $data->name . '</a>';
            })
            ->addColumn('client', function($data) {
                return '<a href="' . route('clients.show', $data->client) . '">' . $data->client->name . '</a>';
            })
            ->addColumn('status_badge', function($data) {
                return Blade::render('<x-project.ui.status-badge :text="true" :status="$status" />', ['status' => $data->status]);
            })
            ->addColumn('team', function($data) {
                $team = '';
                foreach ($data->team as $key => $user) {
                    $team .= Blade::render('<x-site.ui.user-icon :user="$user" />', ['user' => $user]);
                }
                return $team;
            })
            ->addColumn('buttons', function($data) {
                $buttons = '<a href="' . route('projects.edit', $data) . '" class="btn btn-xs btn-dark"><i class="fas fa-pencil-alt"></i></a> ';
                $buttons .= '<a href="' . route('projects.show', $data) . '" class="btn btn-xs btn-info"><i class="fas fa-eye"></i></a> ';
                $buttons .= view('projects.partials.buttons', ['project' => $data, 'buttonSize' => 'xs', 'hideButtonText' => '', 'type' => 'table']);
                return $buttons;
            })
            ->addColumn('amount', function($data) {
                return number_format($data->amount, 2);
            })
            ->addColumn('time_plan', function($data) {
                return '<span class="text-' . ($data->overdue ? 'danger' : 'body') . '">' . $data->time_plan . ' %' . '</span>';
            })
            ->addColumn('total_time', function($data) {
                return $data->total_time . ' Hours';
            })            
            ->addColumn('budget_plan', function($data) {
                return '<span class="text-' . ($data->overdue ? 'danger' : 'body') . '">' . $data->budget_plan . ' %' . '</span>';
            })
            ->editColumn('due_date', function($data) {
                return '<span class="text-' . ($data->overdue ? 'danger' : 'body') . '">' . Carbon::createFromFormat('Y-m-d H:i:s', $data->due_date)->format('d.m.Y') . '</span>';
            })
            ->rawColumns(['detail', 'client', 'team', 'buttons', 'due_date', 'time_plan', 'budget_plan'])
            ->make(true);
    }
}