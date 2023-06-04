<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;
use App\Http\Requests\User\LoadUserByProjectRequest;

class UserLoadByProjectController extends Controller
{
    /**
     * Load Users by project id
     */
    public function __invoke(LoadUserByProjectRequest $request): JsonResponse
    {
        $data['users'] = User::whereHas('projects', function (Builder $query) use ($request) {
                            $query->where('project_id', $request->project_id);
                        })->get([DB::raw("concat(name, ' ', surname) as fullname"), 'id']);
        return response()->json($data);
    }
}
