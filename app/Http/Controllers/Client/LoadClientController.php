<?php

namespace App\Http\Controllers\Client;

use Carbon\Carbon;
use Yajra\DataTables\DataTables;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Models\Client;

class LoadClientController extends Controller
{
    /**
     * Load clients to datatables.
     */
    public function __invoke(): JsonResponse
    {
        $data = Client::select('id', 'name', 'email', 'created_at', 'contact_person', 'is_marked')->get();
        return Datatables::of($data)->addIndexColumn()
            ->addColumn('detail', function($data) {
                return '<a href="' . route('clients.show', $data) . '">' . $data->name . '</a>';
            })
            ->addColumn('buttons', function($data) {
                $buttons = '<a href="' . route('clients.edit', $data) . '" class="btn btn-xs btn-dark mr-1"><i class="fas fa-pencil-alt"></i></a>';
                $buttons .= '<a href="' . route('clients.show', $data) . '" class="btn btn-xs btn-info mr-1"><i class="fas fa-eye"></i></a>';
                $buttons .= '<a href="#" class="btn btn-xs btn-primary" onclick="markClient(\'' . route('clients.mark', $data) . '\')"><i class="' . ($data->is_marked ? 'fas' : 'far') . ' fa-bookmark" id="client-' . $data->id . '-marked"></i></a>';
                return $buttons;
            })
            ->editColumn('email', function($data) {
                return $data->email_label;
            })
            ->editColumn('created_at', function($data) {
                $formatedDate = Carbon::createFromFormat('Y-m-d H:i:s', $data->created_at)->format('d.m.Y');
                return $formatedDate;
            })
            ->rawColumns(['detail', 'buttons'])
            ->make(true);
    }
}
