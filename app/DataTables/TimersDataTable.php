<?php

namespace App\DataTables;

use App\Models\Timer;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Support\Facades\Blade;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class TimersDataTable extends DataTable
{
    public function dataTable($query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->setRowId('id')
            ->editColumn('project.name', function (Timer $timer) {
                return '<a href="'.route('projects.show', $timer->project).'">'.$timer->project->name.'</a>';
            })
            ->editColumn('rate.name', function (Timer $timer) {
                return '<a href="'.route('users.rates.edit', ['user' => $timer->user, 'rate' => $timer->rate]).'">'.$timer->rate->name.'</a>';
            })
            ->editColumn('user.full_name', function (Timer $timer) {
                return Blade::render('<x-site.ui.user-icon :user="$user" />', ['user' => $timer->user]);
            })
            ->editColumn('since_at', function (Timer $timer) {
                return Carbon::createFromFormat('Y-m-d H:i:s', $timer->since_at)->format('d.m.Y H:i');
            })
            ->editColumn('until_at', function (Timer $timer) {
                return $timer->until_at ? Carbon::createFromFormat('Y-m-d H:i:s', $timer->until_at)->format('d.m.Y H:i') : 'NaN';
            })
            ->editColumn('created_at', function (Timer $timer) {
                return Carbon::createFromFormat('Y-m-d H:i:s', $timer->created_at)->format('d.m.Y').(($timer->note) ? ' <i class="fas fa-info-circle" data-toggle="tooltip" title="'.$timer->note.'"></i>' : '');
            })
            ->editColumn('buttons', function (Timer $timer) {
                return $timer->until_at ? '<a href="'.route('projects.timers.edit', ['project' => $timer->project, 'timer' => $timer]).'" class="btn btn-xs btn-dark mr-1"><i class="fas fa-pencil-alt"></i></a><a href="#" class="btn btn-xs btn-danger" onclick="deleteTimer(\''.route('projects.timers.destroy', ['project' => $timer->project, 'timer' => $timer]).'\', \'table\', \'#timers-table\', null)"><i class="fas fa-trash"></i></a>' : 'NaN';
            })
            ->rawColumns(['created_at', 'project.name', 'rate.name', 'user.full_name', 'buttons']);
    }

    public function query(Timer $model): QueryBuilder
    {
        return $model->when(
            $this->project_id ?? false,
            fn ($query, $value) => $query->where('timers.project_id', $value)
        )->with('project:id,name', 'rate:id,name,value', 'user:id,avatar_id,name,surname,deleted_at', 'user.avatar:id,path')->select('timers.*')->newQuery();
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId($this->table_identifier ?? 'timers-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(7)
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
            Column::make('project.name')->data('project.name')->title('Project')->visible($this->view === 'project' ? false : true),
            Column::make('rate.name')->data('rate.name')->title('Rate'),
            Column::make('user.name')->data('user.full_name')->title('User'),
            Column::make('total_time')->orderable(false)->searchable(false),
            Column::make('amount')->orderable(false)->searchable(false),
            Column::make('since_at'),
            Column::make('until_at'),
            Column::make('created_at')->title('Date'),
            Column::make('buttons')->title('')->orderable(false)->searchable(false)->visible($this->view === 'analysis' ? false : true),
            Column::make('user.surname')->visible(false),
        ];
    }

    protected function filename(): string
    {
        return 'Timer_'.date('YmdHis');
    }
}
