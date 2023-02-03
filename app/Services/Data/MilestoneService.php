<?php

namespace App\Services\Data;

use App\Enums\Routes\ProjectRouteEnum;
use App\Models\Milestone;
use App\Services\RouteService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\ValidatedInput;

class MilestoneService
{
    /**
     * Save data for milestone.
     */
    public function save(Milestone $milestone, ValidatedInput $inputs): Milestone
    {
        return Milestone::updateOrCreate(
            ['id' => $milestone->id],
            [
                'project_id' => $milestone->project_id ?? $inputs->project_id,
                'owner_id' => $milestone->owner_id ?? $inputs->owner_id,
                'name' => $inputs->name,
                'start_date' => $inputs->start_date,
                'due_date' => $inputs->due_date,
                'colour' => $inputs->colour,
                'description' => $inputs->description,
            ]
        );
    }

    /**
     * Mark selected milestone.
     */
    public function mark(Milestone $milestone): Milestone
    {
        $milestone->is_marked = !$milestone->is_marked;
        $milestone->save();
        return $milestone;
    }

    /**
     * Set up redirect for the action.
     */
    public function setUpRedirect(string $type, Milestone $milestone): RedirectResponse
    {
        $redirectAction = $type ? ProjectRouteEnum::Milestones : ProjectRouteEnum::MilestonesDetail;
        $redirectVars = $type ? ['project' => $milestone->project] : ['project' => $milestone->project, 'milestone' => $milestone];
        return (new RouteService)->redirect($redirectAction->value, $redirectVars);
    }
}