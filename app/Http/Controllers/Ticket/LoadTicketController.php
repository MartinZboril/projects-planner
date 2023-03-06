<?php

namespace App\Http\Controllers\Ticket;

use Carbon\Carbon;
use Yajra\DataTables\DataTables;
use Illuminate\Http\{JsonResponse, Request};
use Illuminate\Support\Facades\Blade;
use App\Http\Controllers\Controller;
use App\Models\Ticket;

class LoadTicketController extends Controller
{
    /**
     * Load tickets to datatables.
     */
    public function __invoke(Request $request): JsonResponse
    {
        $projectId = $request->project_id;
        $type = $request->type;
        $overdue = $request->overdue;

        $data = Ticket::select('id', 'subject', 'reporter_id', 'project_id', 'status', 'due_date', 'created_at', 'priority', 'type', 'is_marked')->when(
            $projectId ?? false,
            fn ($query, $value) => $query->where('project_id', $value)
        )->when(
            $overdue ?? false,
            fn ($query, $value) => $query->overdue()->active()
        )->get();

        return Datatables::of($data)->addIndexColumn()
            ->addColumn('detail', function($data) use($type) {
                return '<a href="' . ($type === 'projects' ? route('projects.tickets.show', ['project' => $data->project, 'ticket' => $data]) : route('tickets.show', $data)) . '">' . $data->subject . '</a>';
            })
            ->addColumn('project', function($data) {
                return '<a href="' . route('projects.show', $data->project) . '">' . $data->project->name . '</a>';
            })
            ->addColumn('reporter', function($data) {
                return Blade::render('<x-site.ui.user-icon :user="$user" />', ['user' => $data->reporter]);
            })            
            ->addColumn('assignee', function($data) {
                $ticket = Ticket::where('id', $data->id)->whereNotNull('assignee_id')->select('assignee_id')->first();
                return Blade::render('<x-site.ui.user-icon :user="$user" />', ['user' => $ticket->assignee ?? null]);
            })
            ->editColumn('date', function($data) {
                return Carbon::createFromFormat('Y-m-d H:i:s', $data->created_at)->format('d.m.Y');
            })
            ->editColumn('due_date', function($data) {
                return '<span class="text-' . ($data->overdue ? 'danger' : 'body') . '">' . Carbon::createFromFormat('Y-m-d H:i:s', $data->due_date)->format('d.m.Y') . '</span>';
            })
            ->addColumn('status_badge', function($data) {
                return Blade::render('<x-ticket.ui.status-badge :text="true" :status="$status" />', ['status' => $data->status]);
            })
            ->addColumn('type', function($data) {
                return Blade::render('<x-ticket.ui.type :type="$type" />', ['type' => $data->type]);
            })
            ->addColumn('priority', function($data) {
                return '<span class="text-' . ($data->urgent ? 'danger font-weight-bold' : 'body') . '">' . Blade::render('<x-ticket.ui.priority :priority="$priority" />', ['priority' => $data->priority]) . '</span>';
            })
            ->addColumn('buttons', function($data) use($type) {
                $buttons = '<a href="' . ($type === 'projects' ? route('projects.tickets.edit', ['project' => $data->project, 'ticket' => $data]) : route('tickets.edit', $data)) . '" class="btn btn-xs btn-dark"><i class="fas fa-pencil-alt"></i></a> ';
                $buttons .= '<a href="' . ($type === 'projects' ? route('projects.tickets.show', ['project' => $data->project, 'ticket' => $data]) : route('tickets.show', $data)) . '" class="btn btn-xs btn-info"><i class="fas fa-eye"></i></a> ';
                $buttons .= view('tickets.partials.buttons', ['ticket' => $data, 'buttonSize' => 'xs', 'hideButtonText' => '', 'type' => 'table']);
                return $buttons;
            })
            ->rawColumns(['detail', 'project', 'reporter', 'assignee', 'user', 'buttons', 'priority', 'due_date'])
            ->make(true);
    }
}