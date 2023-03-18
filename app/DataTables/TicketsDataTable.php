<?php

namespace App\DataTables;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Support\Facades\Blade;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;
use App\Models\Ticket;

class TicketsDataTable extends DataTable
{
    public function dataTable($query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
                    ->setRowId('id')
                    ->editColumn('subject', function(Ticket $ticket) {
                        return '<a href="' . ($this->view === 'project' ? route('projects.tickets.show', ['project' => $ticket->project, 'ticket' => $ticket]) : route('tickets.show', $ticket)) . '">' . $ticket->subject . '</a>';
                    })
                    ->editColumn('project.name', function(Ticket $ticket) {
                        return '<a href="' . route('projects.show', $ticket->project) . '">' . $ticket->project->name . '</a>';
                    })
                    ->editColumn('reporter.full_name', function(Ticket $ticket) {
                        return Blade::render('<x-site.ui.user-icon :user="$reporter" />', ['reporter' => $ticket->reporter]);
                    })    
                    ->editColumn('assignee.full_name', function(Ticket $ticket) {
                        return Blade::render('<x-site.ui.user-icon :user="$assignee" />', ['assignee' => $ticket->assignee]);
                    })                                            
                    ->editColumn('created_at', function(Ticket $ticket) {
                        return Carbon::createFromFormat('Y-m-d H:i:s', $ticket->created_at)->format('d.m.Y');
                    })
                    ->editColumn('due_date', function(Ticket $ticket) {
                        return '<span class="text-' . ($ticket->overdue ? 'danger' : 'body') . '">' . Carbon::createFromFormat('Y-m-d H:i:s', $ticket->due_date)->format('d.m.Y') . '</span>';
                    })
                    ->editColumn('status', function(Ticket $ticket) {
                        return Blade::render('<x-ticket.ui.status-badge :text="true" :status="$status" />', ['status' => $ticket->status]);
                    })
                    ->editColumn('type', function(Ticket $ticket) {
                        return Blade::render('<x-ticket.ui.type :type="$type" />', ['type' => $ticket->type]);
                    })
                    ->editColumn('priority', function(Ticket $ticket) {
                        return '<span class="text-' . ($ticket->urgent ? 'danger font-weight-bold' : 'body') . '">' . Blade::render('<x-ticket.ui.priority :priority="$priority" />', ['priority' => $ticket->priority]) . '</span>';
                    })                    
                    ->editColumn('buttons', function(Ticket $ticket) {
                        $buttons = '<a href="' . ($this->view === 'project' ? route('projects.tickets.edit', ['project' => $ticket->project, 'ticket' => $ticket]) : route('tickets.edit', $ticket)) . '" class="btn btn-xs btn-dark"><i class="fas fa-pencil-alt"></i></a> ';
                        $buttons .= '<a href="' . ($this->view === 'project' ? route('projects.tickets.show', ['project' => $ticket->project, 'ticket' => $ticket]) : route('tickets.show', $ticket)) . '" class="btn btn-xs btn-info"><i class="fas fa-eye"></i></a> ';
                        $buttons .= view('tickets.partials.buttons', ['ticket' => $ticket, 'buttonSize' => 'xs', 'hideButtonText' => '', 'type' => 'table']);
                        return $buttons;
                    })
                    ->editColumn('assignee.surname', function(Ticket $ticket) {
                        return $ticket->assignee ?? false ? $ticket->assignee->surname : null;
                    })   
                    ->rawColumns(['subject', 'project.name', 'reporter.full_name', 'assignee.full_name', 'created_at', 'priority', 'due_date', 'buttons']);
    }

    public function query(Ticket $model): QueryBuilder
    {
        return $model->when(
            $this->project_id ?? false,
            fn ($query, $value) => $query->where('project_id', $value)
        )->with('project', 'reporter', 'assignee')->select('tickets.*')->newQuery();
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('tickets-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    ->orderBy(8)
                    ->parameters([
                        'responsive' => true,
                        'autoWidth' => false,
                        'lengthMenu' => [
                            [ 10, 25, 50, -1 ],
                            [ '10 rows', '25 rows', '50 rows', 'Show all' ]
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
            Column::make('project.name')->data('project.name')->title('Project')->visible($this->view === 'project' ?? false ? false : true),
            Column::make('reporter.name')->data('reporter.full_name')->title('Reporter'),
            Column::make('assignee.name')->data('assignee.full_name')->title('Assignee'),
            Column::make('created_at'),
            Column::make('status')->orderable(false)->searchable(false),
            Column::make('type')->orderable(false)->searchable(false),
            Column::make('priority')->orderable(false)->searchable(false),
            Column::make('due_date'),
            Column::make('buttons')->title('')->orderable(false)->searchable(false),
            Column::make('reporter.surname')->visible(false),
            Column::make('assignee.surname')->visible(false),
        ];
    }

    protected function filename(): string
    {
        return 'Ticket_' . date('YmdHis');
    }
}
