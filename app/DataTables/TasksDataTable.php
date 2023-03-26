<?php

namespace App\DataTables;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Support\Facades\Blade;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;
use App\Models\Task;

class TasksDataTable extends DataTable
{
    public function dataTable($query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
                    ->setRowId('id')
                    ->editColumn('name', function(Task $task) {
                        return '<a href="' . ($this->view === 'project' ? route('projects.tasks.show', ['project' => $task->project, 'task' => $task]) : route('tasks.show', $task)) . '">' . $task->name . '</a>';
                    })                    
                    ->editColumn('project.name', function(Task $task) {
                        return '<a href="' . route('projects.show', $task->project) . '">' . $task->project->name . '</a>';
                    })
                    ->editColumn('milestone.name', function(Task $task) {
                        if ($task->milestone ?? false) {
                            return '<a href="' . route('projects.milestones.show', ['project' => $task->project, 'milestone' => $task->milestone]) . '">' . $task->milestone->name . '</a>';                
                        }
                        return 'NaN';
                    })
                    ->editColumn('user.full_name', function(Task $task) {
                        return Blade::render('<x-site.ui.user-icon :user="$user" />', ['user' => $task->user]);
                    })  
                    ->editColumn('due_date', function(Task $task) {
                        return '<span class="text-' . ($task->overdue ? 'danger' : 'body') . '">' . Carbon::createFromFormat('Y-m-d H:i:s', $task->due_date)->format('d.m.Y') . '</span>';
                    })
                    ->editColumn('status', function(Task $task) {
                        return Blade::render('<x-task.ui.status-badge :text="true" :task="$task" />', ['task' => $task]);
                    })                    
                    ->editColumn('buttons', function(Task $task) {
                        $buttons = '<a href="' . ($this->view === 'project' ? route('projects.tasks.edit', ['project' => $task->project, 'task' => $task]) : route('tasks.edit', $task)) . '" class="btn btn-xs btn-dark"><i class="fas fa-pencil-alt"></i></a> ';
                        $buttons .= '<a href="' . ($this->view === 'project' ? route('projects.tasks.show', ['project' => $task->project, 'task' => $task]) : route('tasks.show', $task)) . '" class="btn btn-xs btn-info"><i class="fas fa-eye"></i></a> ';
                        $buttons .= view('tasks.partials.buttons', ['task' => $task, 'buttonSize' => 'xs', 'hideButtonText' => '', 'type' => 'table', 'tableIdentifier' => '#' . ($this->table_identifier ?? 'tasks-table')]);
                        return $buttons;
                    })
                    ->rawColumns(['name', 'project.name', 'milestone.name', 'user.full_name', 'status', 'due_date', 'buttons']);
    }

    public function query(Task $model): QueryBuilder
    {
        return $model->when(
            $this->project_id ?? false,
            fn ($query, $value) => $query->where('project_id', $value)
        )->when(
            $this->milestone_id ?? false,
            fn ($query, $value) => $query->where('milestone_id', $value)
        )->when(
            $this->newed ?? false,
            fn ($query, $value) => $query->where('status', 1)
        )->with('project', 'milestone', 'user')->select('tasks.*')->newQuery();
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId($this->table_identifier ?? 'tasks-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    ->orderBy(4)
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
            Column::make('name'),
            Column::make('project.name')->data('project.name')->title('Project')->visible($this->view === 'project' ? false : true),
            Column::make('milestone.name')->data('milestone.name')->title('Milestone')->visible($this->view === 'milestone' ? false : true),
            Column::make('user.name')->data('user.full_name')->title('User'),
            Column::make('due_date'),
            Column::make('status')->orderable(false)->searchable(false),
            Column::make('buttons')->title('')->orderable(false)->searchable(false)->visible(in_array($this->view, ['analysis', 'milestone']) ? false : true),
            Column::make('user.surname')->visible(false),
        ];
    }

    protected function filename(): string
    {
        return 'Task_' . date('YmdHis');
    }
}
