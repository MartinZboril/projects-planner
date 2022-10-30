<?php

namespace App\Services;

use App\Models\Milestone;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Session;

class MilestoneService
{
    public function store(array $fields): Milestone
    {
        $milestone = new Milestone;
        $milestone->project_id = $fields['project_id'];
        $milestone->owner_id = $fields['owner_id'];
        $milestone->name = $fields['name'];
        $milestone->start_date = $fields['start_date'];
        $milestone->end_date = $fields['end_date'];
        $milestone->colour = $fields['colour'];
        $milestone->description = $fields['description'];
        $milestone->save();

        return $milestone;
    }

    public function update(Milestone $milestone, array $fields): Milestone
    {
        Milestone::where('id', $milestone->id)
                    ->update([
                        'owner_id' => $fields['owner_id'],
                        'name' => $fields['name'],
                        'start_date' => $fields['start_date'],
                        'end_date' => $fields['end_date'],
                        'colour' => $fields['colour'],
                        'description' => $fields['description'],
                    ]);

        return $milestone;
    }

    public function flash(string $action): void
    {
        switch ($action) {
            case 'create':
                Session::flash('message', __('messages.milestone.create'));
                Session::flash('type', 'info');
                break;
            case 'update':
                Session::flash('message', __('messages.milestone.update'));
                Session::flash('type', 'info');
                break;
            default:
                Session::flash('message', __('messages.complete'));
                Session::flash('type', 'info');
        } 
    }

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