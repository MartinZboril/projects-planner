<?php

namespace App\DataTables;
 
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;
use App\Models\User;

class UsersDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
                    ->setRowId('id')
                    ->editColumn('name', function(User $user) {
                        return '<a href="' . route('users.show', $user) . '">' . $user->name . ' ' . $user->surname . '</a>';
                    })
                    ->editColumn('created_at', function(User $user) {
                        $formatedDate = Carbon::createFromFormat('Y-m-d H:i:s', $user->created_at)->format('d.m.Y');
                        return $formatedDate;
                    })
                    ->editColumn('buttons', function(User $user) {
                        $buttons = '<a href="' . route('users.edit', $user) . '" class="btn btn-xs btn-dark mr-1"><i class="fas fa-pencil-alt"></i></a>';
                        $buttons .= '<a href="' . route('users.show', $user) . '" class="btn btn-xs btn-info"><i class="fas fa-eye"></i></a>';
                        return $buttons;
                    })
                    ->rawColumns(['name', 'buttons']);
    }
 
    public function query(User $model): QueryBuilder
    {
        return $model->select('id', 'name', 'surname', 'email', 'job_title', 'mobile', 'city', 'created_at')->newQuery();
    }
 
    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('users-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    ->orderBy(1)
                    ->parameters([
                        'responsive' => true,
                        'autoWidth' => false,
                        'dom' => 'Bfrtip',
                        'lengthMenu' => [
                            [ 10, 25, 50, -1 ],
                            [ '10 rows', '25 rows', '50 rows', 'Show all' ]
                        ],  
                        'buttons' => [
                            'pageLength',
                        ],
                    ])
                    ->language([
                        'processing' => '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span>'
                    ]);
    }
 
    public function getColumns(): array
    {
        return [
            Column::make('name')->title('Detail'),
            Column::make('email'),
            Column::make('job_title'),
            Column::make('mobile'),
            Column::make('city'),
            Column::make('created_at'),
            Column::make('buttons'),
        ];
    }
 
    protected function filename(): string
    {
        return 'Users_'.date('YmdHis');
    }
}