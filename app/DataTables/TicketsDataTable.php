<?php

namespace App\DataTables;

use App\Models\Ticket;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Support\Facades\Blade;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class TicketsDataTable extends DataTable
{
    public function dataTable($query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->setRowId('id')
            ->editColumn('subject', function (Ticket $ticket) {
                return '<a href="'.($this->view === 'project' ? route('projects.tickets.show', ['project' => $ticket->project, 'ticket' => $ticket]) : route('tickets.show', $ticket)).'">'.$ticket->subject.'</a>';
            })
            ->editColumn('project.name', function (Ticket $ticket) {
                return '<a href="'.route('projects.show', $ticket->project).'">'.$ticket->project->name.'</a>';
            })
            ->editColumn('reporter.full_name', function (Ticket $ticket) {
                return Blade::render('<x-site.ui.user-icon :user="$reporter" />', ['reporter' => $ticket->reporter]);
            })
            ->editColumn('assignee.full_name', function (Ticket $ticket) {
                return Blade::render('<x-site.ui.user-icon :user="$assignee" />', ['assignee' => $ticket->assignee]);
            })
            ->editColumn('created_at', function (Ticket $ticket) {
                return Carbon::createFromFormat('Y-m-d H:i:s', $ticket->created_at)->format('d.m.Y');
            })
            ->editColumn('dued_at', function (Ticket $ticket) {
                return '<span class="text-'.($ticket->deadline_overdue ? 'danger' : 'body').'">'.Carbon::createFromFormat('Y-m-d H:i:s', $ticket->dued_at)->format('d.m.Y').'</span>';
            })
            ->editColumn('status', function (Ticket $ticket) {
                return Blade::render('<x-ticket.ui.status-badge :text="true" :status="$status" />', ['status' => $ticket->status]);
            })
            ->editColumn('task.name', function (Ticket $ticket) {
                if ($ticket->task ?? false) {
                    if ($this->view === 'project') {
                        return '<a href="'.route('projects.tasks.show', ['project' => $ticket->project, 'task' => $ticket->task]).'">'.$ticket->task->name.'</a>';
                    } else {
                        return '<a href="'.route('tasks.show', ['project' => $ticket->project, 'task' => $ticket->task]).'">'.$ticket->task->name.'</a>';
                    }
                }

                return 'NaN';
            })
            ->editColumn('type', function (Ticket $ticket) {
                return Blade::render('<x-ticket.ui.type :type="$type" />', ['type' => $ticket->type]);
            })
            ->editColumn('priority', function (Ticket $ticket) {
                return '<span class="text-'.($ticket->urgent ? 'danger font-weight-bold' : 'body').'">'.Blade::render('<x-ticket.ui.priority :priority="$priority" />', ['priority' => $ticket->priority]).'</span>';
            })
            ->editColumn('buttons', function (Ticket $ticket) {
                $buttons = '<a href="'.($this->view === 'project' ? route('projects.tickets.edit', ['project' => $ticket->project, 'ticket' => $ticket]) : route('tickets.edit', $ticket)).'" class="btn btn-xs btn-dark"><i class="fas fa-pencil-alt"></i></a> ';
                $buttons .= '<a href="'.($this->view === 'project' ? route('projects.tickets.show', ['project' => $ticket->project, 'ticket' => $ticket]) : route('tickets.show', $ticket)).'" class="btn btn-xs btn-info"><i class="fas fa-eye"></i></a> ';
                $buttons .= view('tickets.partials.buttons', ['ticket' => $ticket, 'buttonSize' => 'xs', 'hideButtonText' => '', 'type' => 'table', 'tableIdentifier' => '#'.($this->table_identifier ?? 'tickets-table')]);

                return $buttons;
            })
            ->editColumn('assignee.surname', function (Ticket $ticket) {
                return $ticket->assignee ?? false ? $ticket->assignee->surname : null;
            })
            ->rawColumns(['subject', 'project.name', 'reporter.full_name', 'assignee.full_name', 'created_at', 'priority', 'dued_at', 'buttons', 'task.name']);
    }

    public function query(Ticket $model): QueryBuilder
    {
        return $model->when(
            $this->project_id ?? false,
            fn ($query, $value) => $query->where('tickets.project_id', $value)
        )->when(
            $this->unassigned ?? false,
            fn ($query, $value) => $query->unassigned()->active()
        )->with('project:id,name', 'reporter:id,avatar_id,name,surname', 'reporter.avatar:id,path', 'assignee:id,avatar_id,name,surname', 'assignee.avatar:id,path', 'task:id,name,ticket_id')->select('tickets.*')->newQuery();
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId($this->table_identifier ?? 'tickets-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(9)
            ->parameters([
                'responsive' => true,
                'autoWidth' => false,
                'lengthMenu' => [
                    [10, 25, 50, -1],
                    ['10 rows', '25 rows', '50 rows', 'Show all'],
                ],
                'buttons' => [
                    'pageLength',
                ],
            ]);
    }

    protected function getColumns(): array
    {
        return [
            Column::make('subject')->title('Ticket'),
            Column::make('project.name')->data('project.name')->title('Project')->visible($this->view === 'project' ? false : true),
            Column::make('reporter.name')->data('reporter.full_name')->title('Reporter'),
            Column::make('assignee.name')->data('assignee.full_name')->title('Assignee'),
            Column::make('created_at'),
            Column::make('status')->orderable(false)->searchable(false),
            Column::make('task.name')->data('task.name')->title('Converted Task'),
            Column::make('type')->orderable(false)->searchable(false),
            Column::make('priority')->orderable(false)->searchable(false),
            Column::make('dued_at'),
            Column::make('buttons')->title('')->orderable(false)->searchable(false)->visible($this->view === 'analysis' ? false : true),
            Column::make('reporter.surname')->visible(false),
            Column::make('assignee.surname')->visible(false),
        ];
    }

    protected function filename(): string
    {
        return 'Ticket_'.date('YmdHis');
    }
}
