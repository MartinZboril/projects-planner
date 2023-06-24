<?php

namespace App\DataTables;

use App\Models\Role;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class RolesDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->setRowId('id')
            ->editColumn('name', function (Role $role) {
                return '<a href="'.route('users.roles.edit', $role).'">'.$role->name.'</a>';
            })
            ->editColumn('is_active', function (Role $role) {
                return $role->is_active ? 'Yes' : 'No';
            })
            ->editColumn('buttons', function (Role $role) {
                return '<a href="'.route('users.roles.edit', $role).'" class="btn btn-xs btn-dark mr-1"><i class="fas fa-pencil-alt"></i></a><a href="#" class="btn btn-xs btn-danger" onclick="deleteRole(\''.route('users.roles.destroy', $role).'\', \'table\', \'#roles-table\', null)"><i class="fas fa-trash"></i></a>';
            })
            ->rawColumns(['name', 'buttons']);
    }

    public function query(Role $model): QueryBuilder
    {
        return $model->select('id', 'name', 'is_active')->newQuery();
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId($this->table_identifier ?? 'roles-table')
            ->columns($this->getColumns())
            ->orderBy(0)
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
            Column::make('name')->title('Role'),
            Column::make('is_active'),
            Column::make('buttons')->title('')->orderable(false)->searchable(false),
        ];
    }

    protected function filename(): string
    {
        return 'Roles_'.date('YmdHis');
    }
}
