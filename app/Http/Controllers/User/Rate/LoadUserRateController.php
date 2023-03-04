<?php

namespace App\Http\Controllers\User\Rate;

use Yajra\DataTables\DataTables;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Models\User;

class LoadUserRateController extends Controller
{
    /**
     * Load users rates to datatables.
     */
    public function __invoke(User $user): JsonResponse
    {
        $data = $user->rates()->select('id', 'user_id', 'name', 'is_active', 'value', 'note')->get();
        return Datatables::of($data)->addIndexColumn()
            ->addColumn('note_popover', function($data) {
                return ($data->note) ? '<i class="fas fa-info-circle" data-toggle="popover" title="Note" data-content="' . $data->note . '"></i>' : '#';
            })
            ->addColumn('detail', function($data) {
                return '<a href="' . route('users.rates.edit', ['user' => $data->user, 'rate' => $data]) . '">' . $data->name . '</a>';
            })
            ->editColumn('is_active', function($data) {
                return $data->is_active ? 'Yes' : 'No';
            })
            ->editColumn('value', function($data) {
                return number_format($data->value, 2);
            })
            ->addColumn('buttons', function($data) {
                return '<a href="' . route('users.rates.edit', ['user' => $data->user, 'rate' => $data]) . '" class="btn btn-xs btn-dark"><i class="fas fa-pencil-alt"></i></a>';
            })
            ->rawColumns(['note_popover', 'detail', 'buttons'])
            ->make(true);
    }
}
