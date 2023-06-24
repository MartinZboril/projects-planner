<?php

namespace App\DataTables;

use App\Models\Project;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Support\Facades\Blade;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class ProjectsDataTable extends DataTable
{
    public function dataTable($query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->setRowId('id')
            ->editColumn('name', function (Project $project) {
                return '<a href="'.route('projects.show', $project).'">'.$project->name.'</a>';
            })
            ->editColumn('client.name', function (Project $project) {
                return '<a href="'.route('clients.show', $project->client).'">'.$project->client->name.'</a>';
            })
            ->editColumn('status', function (Project $project) {
                return Blade::render('<x-project.ui.status-badge :text="true" :status="$status" />', ['status' => $project->status]);
            })
            ->editColumn('team', function (Project $project) {
                $team = '';
                foreach ($project->team as $key => $user) {
                    $team .= Blade::render('<x-site.ui.user-icon :user="$user" />', ['user' => $user]);
                }

                return $team;
            })
            ->editColumn('amount', function (Project $project) {
                return number_format($project->amount, 2);
            })
            ->editColumn('time_plan', function (Project $project) {
                return '<span class="text-'.($project->time_plan_overdue ? 'danger' : 'body').'">'.$project->time_plan.' %'.'</span>';
            })
            ->editColumn('total_time', function (Project $project) {
                return $project->total_time.' Hours';
            })
            ->editColumn('budget_plan', function (Project $project) {
                return '<span class="text-'.($project->budget_overdue ? 'danger' : 'body').'">'.$project->budget_plan.' %'.'</span>';
            })
            ->editColumn('dued_at', function (Project $project) {
                return '<span class="text-'.($project->deadline_overdue ? 'danger' : 'body').'">'.Carbon::createFromFormat('Y-m-d H:i:s', $project->dued_at)->format('d.m.Y').'</span>';
            })
            ->editColumn('buttons', function (Project $project) {
                $buttons = '<a href="'.route('projects.edit', $project).'" class="btn btn-xs btn-dark"><i class="fas fa-pencil-alt"></i></a> ';
                $buttons .= '<a href="'.route('projects.show', $project).'" class="btn btn-xs btn-info"><i class="fas fa-eye"></i></a> ';
                $buttons .= view('projects.partials.buttons', ['project' => $project, 'buttonSize' => 'xs', 'hideButtonText' => '', 'type' => 'table', 'tableIdentifier' => '#'.($this->table_identifier ?? 'projects-table'), 'redirect' => null]);

                return $buttons;
            })
            ->rawColumns(['name', 'client.name', 'status', 'team', 'buttons', 'dued_at', 'time_plan', 'budget_plan']);
    }

    public function query(Project $model): QueryBuilder
    {
        return $model->with('client:id,name', 'team:id,avatar_id,name,surname', 'team.avatar:id,path', 'timers:id,project_id,rate_id,since_at,until_at', 'timers.rate:id,value')->select('projects.*')->newQuery();
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId($this->table_identifier ?? 'projects-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(4)
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
            Column::make('name')->title('Project'),
            Column::make('client.name')->data('client.name')->title('Client'),
            Column::make('status')->orderable(false)->searchable(false),
            Column::make('team')->orderable(false)->searchable(false),
            Column::make('dued_at'),
            Column::make('time_plan')->orderable(false)->searchable(false),
            Column::make('total_time')->orderable(false)->searchable(false),
            Column::make('budget_plan')->orderable(false)->searchable(false),
            Column::make('amount')->orderable(false)->searchable(false),
            Column::make('buttons')->title('')->orderable(false)->searchable(false)->visible($this->view === 'analysis' ? false : true),
        ];
    }

    protected function filename(): string
    {
        return 'Project_'.date('YmdHis');
    }
}
