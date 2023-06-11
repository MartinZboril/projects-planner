<?php

namespace App\DataTables;
 
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Support\Facades\Blade;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;
use App\Models\User;

class UsersDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
                    ->setRowId('id')
                    ->filterColumn('name', function($query, $keyword) {
                        $sql = "CONCAT(users.name, '-' , users.surname)  like ?";
                        $query->whereRaw($sql, ["%{$keyword}%"]);
                    })
                    ->editColumn('name', function(User $user) {
                        return Blade::render('<x-site.ui.user-icon :user="$user" />', ['user' => $user]) . '<a href="' . route('users.show', $user) . '" class="ml-1">' . $user->name . ' ' . $user->surname . '</a>';
                    })
                    ->editColumn('job_title', function(User $user) {
                        return $user->job_title_label;
                    })
                    ->editColumn('mobile', function(User $user) {
                        return $user->mobile_label;
                    })
                    ->editColumn('city', function(User $user) {
                        return $user->address->city_label;
                    })
                    ->editColumn('created_at', function(User $user) {
                        $formatedDate = Carbon::createFromFormat('Y-m-d H:i:s', $user->created_at)->format('d.m.Y');
                        return $formatedDate;
                    })
                    ->editColumn('buttons', function(User $user) {
                        $buttons = '<a href="' . route('users.edit', $user) . '" class="btn btn-xs btn-dark"><i class="fas fa-pencil-alt"></i></a> ';
                        $buttons .= '<a href="' . route('users.show', $user) . '" class="btn btn-xs btn-info"><i class="fas fa-eye"></i></a>';
                        return $buttons;
                    })
                    ->rawColumns(['name', 'buttons']);
    }
 
    public function query(User $model): QueryBuilder
    {
        return $model->with('address')->select('id', 'address_id', 'name', 'surname', 'email', 'job_title', 'mobile', 'created_at', 'avatar_id')->newQuery();
    }
 
    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId($this->table_identifier ?? 'users-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    ->orderBy(5)
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
 
    public function getColumns(): array
    {
        return [
            Column::make('name')->title('User'),
            Column::make('email'),
            Column::make('job_title'),
            Column::make('mobile'),
            Column::make('city'),
            Column::make('created_at'),
            Column::make('buttons')->title('')->orderable(false)->searchable(false),
        ];
    }
 
    protected function filename(): string
    {
        return 'Users_'.date('YmdHis');
    }
}