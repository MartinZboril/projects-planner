<?php

namespace App\Http\Controllers;

use App\Http\Requests\Milestone\LoadMilestioneByProjectRequest;
use App\Models\Milestone;
use Illuminate\Http\JsonResponse;

class MilestoneLoadByProjectController extends Controller
{
    /**
     * Load Milestones by project id
     */
    public function __invoke(LoadMilestioneByProjectRequest $request): JsonResponse
    {
        $data['milestones'] = Milestone::where('project_id', $request->project_id)->get(['name', 'id']);

        return response()->json($data);
    }
}
