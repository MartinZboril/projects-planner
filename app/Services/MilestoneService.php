<?php

namespace App\Services;

use App\Models\Milestone;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class MilestoneService
{
    public function store(Request $request): Milestone
    {
        $milestone = new Milestone;
        $milestone->name = $request->name;
        $milestone->project_id = $request->project_id;
        $milestone->owner_id = $request->owner_id;
        $milestone->start_date = $request->start_date;
        $milestone->end_date = $request->end_date;
        $milestone->colour = $request->colour;
        $milestone->description = $request->description;
        $milestone->save();

        return $milestone;
    }

    public function update(Milestone $milestone, Request $request): Milestone
    {
        Milestone::where('id', $milestone->id)
                    ->update([
                        'name' => $request->name,
                        'owner_id' => $request->owner_id,
                        'start_date' => $request->start_date,
                        'end_date' => $request->end_date,
                        'colour' => $request->colour,
                        'description' => $request->description,
                    ]);

        return $milestone;
    }

    public function flash(string $action): void
    {
        switch ($action) {
            case 'create':
                Session::flash('message', 'Milestone was created!');
                Session::flash('type', 'info');
                break;
            case 'update':
                Session::flash('message', 'Milestone was updated!');
                Session::flash('type', 'info');
                break;
            default:
                Session::flash('message', 'Action was completed!');
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