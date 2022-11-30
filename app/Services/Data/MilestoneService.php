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
     * Update milestone.
     */
    public function update(Milestone $milestone, ValidatedInput $inputs): Milestone
    {
        Milestone::where('id', $milestone->id)
                    ->update([
                        'owner_id' => $inputs->owner_id,
                        'name' => $inputs->name,
                        'start_date' => $inputs->start_date,
                        'end_date' => $inputs->end_date,
                        'colour' => $inputs->colour,
                        'description' => $inputs->description,
                    ]);

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