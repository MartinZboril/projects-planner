<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class TicketController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tickets = Ticket::all();

        return view('tickets.index', ['tickets' => $tickets]);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('tickets.create', ['projects' => Project::all(), 'users' => User::all()]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'subject' => ['required', 'string', 'max:255'],
            'project_id' => ['required', 'integer', 'exists:projects,id'],
            'assignee_id' => ['sometimes', 'required', 'integer', 'exists:users,id'],
            'type' => ['required', 'integer', 'in:1,2,3,4'],
            'priority' => ['required', 'integer', 'in:1,2,3,4'],
            'due_date' => ['required', 'date'],
            'message' => ['required', 'max:65553'],
        ]);

        if ($validator->fails()) {
            return redirect()
                    ->route('tickets.create')
                    ->withErrors($validator)
                    ->withInput();
        }

        $ticket = new Ticket();

        $ticket->subject = $request->subject;
        $ticket->project_id = $request->project_id;
        $ticket->reporter_id = Auth::id();
        $ticket->assignee_id = $request->assignee_id;
        $ticket->type = $request->type;
        $ticket->priority = $request->priority;
        $ticket->due_date = $request->due_date;
        $ticket->message = $request->message;

        $ticket->save();

        Session::flash('message', 'Ticket was created!');
        Session::flash('type', 'info');

        return ($request->create_and_close) ? redirect()->route('tickets.index') : redirect()->route('tickets.detail', ['ticket' => $ticket]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Ticket  $ticket
     * @return \Illuminate\Http\Response
     */
    public function detail(Ticket $ticket)
    {
        return view('tickets.detail', ['ticket' => $ticket]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Ticket  $ticket
     * @return \Illuminate\Http\Response
     */
    public function edit(Ticket $ticket)
    {
        return view('tickets.edit', ['ticket' => $ticket, 'projects' => Project::all(), 'users' => User::all()]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Ticket  $ticket
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Ticket $ticket)
    {
        $validator = Validator::make($request->all(), [
            'subject' => ['required', 'string', 'max:255'],
            'project_id' => ['required', 'integer', 'exists:projects,id'],
            'assignee_id' => ['required', 'integer', 'exists:users,id'],
            'type' => ['required', 'integer', 'in:1,2,3,4'],
            'priority' => ['required', 'integer', 'in:1,2,3,4'],
            'status' => ['required', 'integer', 'in:1,2,3'],
            'due_date' => ['required', 'date'],
            'message' => ['required', 'max:65553'],
        ]);

        if ($validator->fails()) {
            return redirect()
                    ->route('tickets.edit', ['ticket' => $ticket->id])
                    ->withErrors($validator)
                    ->withInput();
        }

        Ticket::where('id', $ticket->id)
                    ->update([
                        'subject' => $request->subject,
                        'project_id' => $request->project_id,
                        'assignee_id' => $request->assignee_id,
                        'status' => $request->status,
                        'type' => $request->type,
                        'priority' => $request->priority,
                        'due_date' => $request->due_date,
                        'message' => $request->message,
                    ]);

        Session::flash('message', 'Ticket was updated!');
        Session::flash('type', 'info');

        return ($request->save_and_close) ? redirect()->route('tickets.index') : redirect()->route('tickets.detail', ['ticket' => $ticket->id]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Ticket  $ticket
     * @return \Illuminate\Http\Response
     */
    public function destroy(Ticket $ticket)
    {
        //
    }
}
