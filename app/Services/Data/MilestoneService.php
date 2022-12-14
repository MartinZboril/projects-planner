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
     * Store new milestone.
     */
    public function store(ValidatedInput $inputs): Milestone
    {
        $milestone = new Milestone;
        $milestone->project_id = $inputs->project_id;

        return $this->save($milestone, $inputs);
    }

    /**
     * Update milestone.
     */
    public function update(Milestone $milestone, ValidatedInput $inputs): Milestone
    {
        return $this->save($milestone, $inputs);
    }

    /**
     * Save data for milestone.
     */
    protected function save(Milestone $milestone, ValidatedInput $inputs)
    {
        $milestone->owner_id = $inputs->owner_id;
        $milestone->name = $inputs->name;
        $milestone->start_date = $inputs->start_date;
        $milestone->end_date = $inputs->end_date;
        $milestone->colour = $inputs->colour;
        $milestone->description = $inputs->description;
        $milestone->save();

        return $milestone;
    }

    /**
     * Set up redirect for the action
     */
    public function setUpRedirect(string $type, Milestone $milestone): RedirectResponse
    {
        $redirectAction = $type ? ProjectRouteEnum::Milestones : ProjectRouteEnum::MilestonesDetail;
        $redirectVars = $type ? ['project' => $milestone->project] : ['project' => $milestone->project, 'milestone' => $milestone];
        
        return (new RouteService)->redirect($redirectAction->value, $redirectVars);
    }
}