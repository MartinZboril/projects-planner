<?php

namespace App\Http\Controllers;

use App\Models\Milestone;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class MilestoneController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Project $project)
    {
        return view('milestones.create', ['project' => $project]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Project $project)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'owner_id' => ['required', 'integer', 'exists:users,id'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after:start_date'],
            'colour' => ['required', 'string', 'max:255'],
            'description' => ['required'],
        ]);

        if ($validator->fails()) {
            return redirect()
                    ->route('milestones.create', ['project' => $project])
                    ->withErrors($validator)
                    ->withInput();
        }

        $milestone = new Milestone();

        $milestone->name = $request->name;
        $milestone->project_id = $project->id;
        $milestone->owner_id = $request->owner_id;
        $milestone->start_date = $request->start_date;
        $milestone->end_date = $request->end_date;
        $milestone->colour = $request->colour;
        $milestone->description = $request->description;

        $milestone->save();

        Session::flash('message', 'Milestone was created!');
        Session::flash('type', 'info');

        return ($request->create_and_close) ? redirect()->route('projects.milestones', ['project' => $project]) : redirect()->route('milestones.detail', ['project' => $project, 'milestone' => $milestone]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Milestone  $milestone
     * @return \Illuminate\Http\Response
     */
    public function detail(Project $project, Milestone $milestone)
    {
        return view('milestones.detail', ['project' => $project, 'milestone' => $milestone]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Milestone  $milestone
     * @return \Illuminate\Http\Response
     */
    public function edit(Project $project, Milestone $milestone)
    {
        return view('milestones.edit', ['project' => $project, 'milestone' => $milestone]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Milestone  $milestone
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Project $project, Milestone $milestone)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'owner_id' => ['required', 'integer', 'exists:users,id'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after:start_date'],
            'colour' => ['required', 'string', 'max:255'],
            'description' => ['required'],
        ]);

        if ($validator->fails()) {
            return redirect()
                    ->route('milestones.edit', ['project' => $project, 'milestone' => $milestone])
                    ->withErrors($validator)
                    ->withInput();
        }

        Milestone::where('id', $milestone->id)
                    ->update([
                        'name' => $request->name,
                        'owner_id' => $request->owner_id,
                        'start_date' => $request->start_date,
                        'end_date' => $request->end_date,
                        'colour' => $request->colour,
                        'description' => $request->description,
                    ]);

        Session::flash('message', 'Milestone was updated!');
        Session::flash('type', 'info');

        return ($request->save_and_close) ? redirect()->route('projects.milestones', ['project' => $project]) : redirect()->route('milestones.detail', ['project' => $project, 'milestone' => $milestone]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Milestone  $milestone
     * @return \Illuminate\Http\Response
     */
    public function destroy(Milestone $milestone)
    {
        //
    }
}
