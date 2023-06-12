<?php

namespace App\DataTables;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;
use App\Models\Client;

class ClientsDataTable extends DataTable
{
    public function dataTable($query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
                    ->setRowId('id')
                    ->editColumn('name', function(Client $client) {
                        return '<a href="' . route('clients.show', $client) . '">' . $client->name . '</a>';
                    })
                    ->editColumn('email', function(Client $client) {
                        return $client->email;
                    })
                    ->editColumn('created_at', function(Client $client) {
                        return Carbon::createFromFormat('Y-m-d H:i:s', $client->created_at)->format('d.m.Y');
                    })
                    ->editColumn('buttons', function(Client $client) {
                        $buttons = '<a href="' . route('clients.edit', $client) . '" class="btn btn-xs btn-dark"><i class="fas fa-pencil-alt"></i></a> ';
                        $buttons .= '<a href="' . route('clients.show', $client) . '" class="btn btn-xs btn-info"><i class="fas fa-eye"></i></a> ';
                        $buttons .= view('clients.partials.buttons', ['buttonSize' => 'xs', 'client' => $client, 'type' => 'table', 'tableIdentifier' => '#' . ($this->table_identifier ?? 'clients-table')]);
                        return $buttons;
                    })                    
                    ->rawColumns(['name', 'buttons']);
    }

    public function query(Client $model): QueryBuilder
    {
        return $model->select('id', 'name', 'email', 'created_at', 'contact_person', 'is_marked')->newQuery();
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId($this->table_identifier ?? 'clients-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    ->orderBy(3)
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
            Column::make('name')->title('Client'),
            Column::make('contact_person'),
            Column::make('email'),
            Column::make('created_at'),
            Column::make('buttons')->title('')->orderable(false)->searchable(false),
        ];
    }

    protected function filename(): string
    {
        return 'Client_' . date('YmdHis');
    }
}
