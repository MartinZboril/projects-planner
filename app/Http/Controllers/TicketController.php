<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\Project;
use App\Models\User;
use App\Models\ProjectUser;
use App\Models\Task;
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
     * Open the ticket.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Ticket  $ticket
     * @return \Illuminate\Http\Response
     */
    public function open(Request $request, Ticket $ticket)
    {
        Ticket::where('id', $ticket->id)
                    ->update([
                        'status' => 1,
                    ]);

        Session::flash('message', 'Ticket has been opened!');
        Session::flash('type', 'info');

        return redirect()->route('tickets.detail', ['ticket' => $ticket->id]);
    }

    /**
     * Close the ticket.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Ticket  $ticket
     * @return \Illuminate\Http\Response
     */
    public function close(Request $request, Ticket $ticket)
    {
        Ticket::where('id', $ticket->id)
                    ->update([
                        'status' => 2,
                    ]);

        Session::flash('message', 'Ticket has been closed!');
        Session::flash('type', 'info');

        return redirect()->route('tickets.detail', ['ticket' => $ticket->id]);
    }

    /**
     * Archive the ticket.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Ticket  $ticket
     * @return \Illuminate\Http\Response
     */
    public function archive(Request $request, Ticket $ticket)
    {
        Ticket::where('id', $ticket->id)
                    ->update([
                        'status' => 3,
                    ]);

        Session::flash('message', 'Ticket has been archived!');
        Session::flash('type', 'info');

        return redirect()->route('tickets.detail', ['ticket' => $ticket->id]);
    }

    /**
     * Convert the ticket.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Ticket  $ticket
     * @return \Illuminate\Http\Response
     */
    public function convert(Request $request, Ticket $ticket)
    {
        $task = new Task();

        $task->name = $ticket->subject;
        $task->project_id = $ticket->project_id;
        $task->status_id = 1;
        $task->author_id = Auth::id();
        $task->user_id = $ticket->assignee_id;
        $task->start_date = $ticket->created_at;
        $task->due_date = $ticket->due_date;
        $task->description = $ticket->message;

        $task->save();

        if(!ProjectUser::where('project_id', $ticket->project_id)->where('user_id', Auth::id())->first()) {
            $projectUser = new ProjectUser;

            $projectUser->project_id = $ticket->project_id;
            $projectUser->user_id = Auth::id();

            $projectUser->save();
        }

        if(!ProjectUser::where('project_id', $ticket->project_id)->where('user_id', $ticket->assignee_id)->first()) {
            $projectUser = new ProjectUser;

            $projectUser->project_id = $ticket->project_id;
            $projectUser->user_id = $ticket->assignee_id;

            $projectUser->save();
        }

        Ticket::where('id', $ticket->id)
                    ->update([
                        'status' => 3,
                        'is_convert' => true
                    ]);        

        Session::flash('message', 'Task was created!');
        Session::flash('type', 'info');

        return redirect()->route('tasks.detail', ['task' => $task]);
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
