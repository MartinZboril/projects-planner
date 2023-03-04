<?php

namespace App\Http\Controllers\User;

use Carbon\Carbon;
use Yajra\DataTables\DataTables;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Models\User;

class LoadUserController extends Controller
{
    /**
     * Load users to datatables.
     */
    public function __invoke(): JsonResponse
    {
        $data = User::select('id', 'name', 'surname', 'email', 'job_title', 'mobile', 'city', 'created_at')->get();
        return Datatables::of($data)->addIndexColumn()
            ->addColumn('detail', function($data) {
                return '<a href="' . route('users.show', $data) . '">' . $data->name . ' ' . $data->surname . '</a>';
            })
            ->addColumn('buttons', function($data) {
                $buttons = '<a href="' . route('users.edit', $data) . '" class="btn btn-xs btn-dark mr-1"><i class="fas fa-pencil-alt"></i></a>';
                $buttons .= '<a href="' . route('users.show', $data) . '" class="btn btn-xs btn-info"><i class="fas fa-eye"></i></a>';
                return $buttons;
            })
            ->editColumn('job_title', function($data) {
                return $data->job_title_label;
            })
            ->editColumn('mobile', function($data) {
                return $data->mobile_label;
            })
            ->editColumn('city', function($data) {
                return $data->city_label;
            })
            ->editColumn('created_at', function($data) {
                $formatedDate = Carbon::createFromFormat('Y-m-d H:i:s', $data->created_at)->format('d.m.Y');
                return $formatedDate;
            })
            ->rawColumns(['detail', 'buttons'])
            ->make(true);
    }
}
