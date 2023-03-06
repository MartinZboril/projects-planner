<?php

namespace App\Http\Controllers\Project\Timer;

use Carbon\Carbon;
use Yajra\DataTables\DataTables;
use Illuminate\Http\{JsonResponse, Request};
use Illuminate\Support\Facades\Blade;
use App\Http\Controllers\Controller;
use App\Models\Timer;

class LoadTimerController extends Controller
{
    /**
     * Load milestones to datatables.
     */
    public function __invoke(Request $request): JsonResponse
    {
        $projectId = $request->project_id;

        $data = Timer::select('id', 'project_id', 'user_id', 'rate_id', 'since', 'note', 'until')->when(
            $projectId ?? false,
            fn ($query, $value) => $query->where('project_id', $value)
        )->get();

        return Datatables::of($data)->addIndexColumn()
            ->addColumn('note_popover', function($data) {
                return ($data->note) ? '<i class="fas fa-info-circle" data-toggle="popover" title="Note" data-content="' . $data->note . '"></i>' : '#';
            })
            ->addColumn('project', function($data) {
                return '<a href="' . route('projects.show', $data->project) . '">' . $data->project->name . '</a>';
            })
            ->addColumn('rate', function($data) {
                return '<a href="' . route('users.rates.edit', ['user' => $data->user, 'rate' => $data->rate]) . '">' . $data->rate->name . '</a>';
            })
            ->addColumn('buttons', function($data) {
                return $data->until ? '<a href="' . route('projects.timers.edit', ['project' => $data->project, 'timer' => $data]) . '" class="btn btn-xs btn-dark"><i class="fas fa-pencil-alt"></i></a>' : 'NaN';
            })
            ->addColumn('user', function($data) {
                return Blade::render('<x-site.ui.user-icon :user="$user" />', ['user' => $data->user]);
            })
            ->editColumn('total_time', function($data) {
                return $data->until ? $data->total_time : 'NaN';
            })
            ->editColumn('amount', function($data) {
                return $data->until ? number_format($data->amount, 2) : 'NaN';
            })
            ->addColumn('start', function($data) {
                return Carbon::createFromFormat('Y-m-d H:i:s', $data->since)->format('d.m.Y H:i');
            })
            ->addColumn('stop', function($data) {
                return $data->until ? Carbon::createFromFormat('Y-m-d H:i:s', $data->until)->format('d.m.Y H:i') : 'NaN';
            })
            ->addColumn('date', function($data) {
                return Carbon::createFromFormat('Y-m-d H:i:s', $data->since)->format('d.m.Y');
            })
            ->rawColumns(['note_popover', 'project', 'rate', 'user', 'buttons'])
            ->make(true);
    }
}