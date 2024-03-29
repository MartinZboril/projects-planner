<?php

namespace App\DataTables;

use App\Models\Rate;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class RatesDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->setRowId('id')
            ->editColumn('name', function (Rate $rate) {
                return '<a href="'.route('users.rates.edit', $rate).'">'.$rate->name.'</a>';
            })
            ->editColumn('is_active', function (Rate $rate) {
                return $rate->is_active ? 'Yes' : 'No';
            })
            ->editColumn('value', function (Rate $rate) {
                return number_format($rate->value, 2);
            })
            ->editColumn('buttons', function (Rate $rate) {
                return '<a href="'.route('users.rates.edit', $rate).'" class="btn btn-xs btn-dark mr-1"><i class="fas fa-pencil-alt"></i></a><a href="#" class="btn btn-xs btn-danger" onclick="deleteRate(\''.route('users.rates.destroy', $rate).'\', \'table\', \'#rates-table\', null)"><i class="fas fa-trash"></i></a>';
            })
            ->rawColumns(['note_popover', 'name', 'buttons']);
    }

    public function query(Rate $model): QueryBuilder
    {
        return $model->when(
            $this->user_id ?? false,
            fn ($query, $value) => $query->whereHas('users', function (Builder $query) use ($value) {
                $query->where('user_id', $value);
            })
        )->with('users:id,name,surname')->select('id', 'name', 'value', 'is_active')->newQuery();
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId($this->table_identifier ?? 'rates-table')
            ->columns($this->getColumns())
            ->orderBy(1)
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
            Column::make('name')->title('Rate'),
            Column::make('is_active'),
            Column::make('value'),
            Column::make('buttons')->title('')->orderable(false)->searchable(false),
        ];
    }

    protected function filename(): string
    {
        return 'Rates_'.date('YmdHis');
    }
}
