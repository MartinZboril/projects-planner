<?php

namespace App\Services\Data;

use App\Models\Milestone;
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
     * Get route for the action
     */    
    public function redirect(string $action, Milestone $milestone): RedirectResponse 
    {
        switch ($action) {
            case 'milestone':
                return redirect()->route('milestones.detail', ['project' => $milestone->project, 'milestone' => $milestone]);
                break;
            case 'project_milestones':
                return redirect()->route('projects.milestones', ['project' => $milestone->project]);
                break;
            default:
                return redirect()->back();
        } 
    }
}