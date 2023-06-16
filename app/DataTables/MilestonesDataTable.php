<?php

namespace App\DataTables;

use App\Enums\TaskStatusEnum;
use App\Models\Milestone;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Support\Facades\Blade;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class MilestonesDataTable extends DataTable
{
    public function dataTable($query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->setRowId('id')
            ->filterColumn('project_id', function ($query, $keyword) {
                $sql = 'project_id in (select id from projects where name like ?)';
                $query->whereRaw($sql, ["%{$keyword}%"]);
            })
            ->editColumn('name', function (Milestone $milestone) {
                return '<a href="'.route('projects.milestones.show', ['project' => $milestone->project, 'milestone' => $milestone]).'">'.$milestone->name.'</a>';
            })
            ->editColumn('project.name', function (Milestone $milestone) {
                return '<a href="'.route('projects.show', $milestone->project).'">'.$milestone->project->name.'</a>';
            })
            ->editColumn('owner.full_name', function (Milestone $milestone) {
                return Blade::render('<x-site.ui.user-icon :user="$owner" />', ['owner' => $milestone->owner]);
            })
            ->editColumn('progress', function (Milestone $milestone) {
                return Blade::render('<x-milestone.ui.progress :milestone="$milestone" />', ['milestone' => $milestone]);
            })
            ->editColumn('buttons', function (Milestone $milestone) {
                $buttons = '<a href="'.route('projects.milestones.edit', ['project' => $milestone->project, 'milestone' => $milestone]).'" class="btn btn-xs btn-dark"><i class="fas fa-pencil-alt pt-1 pb-1"></i></a> ';
                $buttons .= '<a href="'.route('projects.milestones.show', ['project' => $milestone->project, 'milestone' => $milestone]).'" class="btn btn-xs btn-info"><i class="fas fa-eye pt-1 pb-1"></i></a> ';
                $buttons .= view('projects.milestones.partials.buttons', ['milestone' => $milestone, 'buttonSize' => 'xs', 'hideButtonText' => '', 'type' => 'table', 'tableIdentifier' => '#'.($this->table_identifier ?? 'milestones-table')]);

                return $buttons;
            })
            ->editColumn('started_at', function (Milestone $milestone) {
                return Carbon::createFromFormat('Y-m-d H:i:s', $milestone->started_at)->format('d.m.Y');
            })
            ->editColumn('dued_at', function (Milestone $milestone) {
                return '<span class="text-'.($milestone->deadline_overdue ? 'danger' : 'body').'">'.Carbon::createFromFormat('Y-m-d H:i:s', $milestone->dued_at)->format('d.m.Y').'</span>';
            })
            ->rawColumns(['name', 'project.name', 'owner.full_name', 'progress', 'dued_at', 'buttons']);
    }

    public function query(Milestone $model): QueryBuilder
    {
        return $model->when(
            $this->project_id ?? false,
            fn ($query, $value) => $query->where('milestones.project_id', $value)
        )->when(
            $this->overdue ?? false,
            fn ($query, $value) => $query->where('milestones.dued_at', '<=', date('Y-m-d'))->whereHas('tasks', function (QueryBuilder $query) {
                $query->where('status', '!=', TaskStatusEnum::complete->value);
            })->orWhere('milestones.dued_at', '<=', date('Y-m-d'))->has('tasks', '=', 0)
        )->with('owner:id,avatar_id,name,surname', 'owner.avatar:id,path', 'project:id,name')->select('milestones.*')->newQuery();
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId($this->table_identifier ?? 'milestones-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(5)
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
            Column::make('name')->title('Milestone'),
            Column::make('project.name')->data('project.name')->title('Project')->visible($this->view === 'project' ? false : true),
            Column::make('owner.name')->data('owner.full_name')->title('Owner'),
            Column::make('progress')->orderable(false)->searchable(false),
            Column::make('started_at'),
            Column::make('dued_at'),
            Column::make('buttons')->title('')->orderable(false)->searchable(false)->visible($this->view === 'analysis' ? false : true),
            Column::make('owner.surname')->visible(false),
        ];
    }

    protected function filename(): string
    {
        return 'Milestone_'.date('YmdHis');
    }
}
