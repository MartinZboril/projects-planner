@extends('layouts.master', ['summernote' => true, 'toaster' => true])

@section('title', __('pages.title.project'))

@section('content')
    <div class="content-wrapper">
        <!-- Content Header -->
        <div class="p-3 mb-3" style="background-color:white;">
            <a href="{{ route('projects.tickets.index', $project) }}" class="btn btn-sm btn-primary text-white"><i class="fas fa-caret-left mr-1"></i>Back</a>
            <a href="{{ route('projects.tickets.edit', ['project' => $project, 'ticket' => $ticket]) }}" class="btn btn-sm btn-primary text-white"><i class="fas fa-pencil-alt mr-1"></i>Edit</a>
            @include('projects.tickets.partials.buttons', ['project' => $project, 'ticket' => $ticket, 'buttonSize' => 'sm', 'buttonText' => true, 'redirect' => 'projects'])
        </div>
        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <!-- Message -->
                @include('site.partials.message', ['message' => Session::get('message'), 'type' => Session::get('type')])
                <!-- Content -->
                <div class="row">
                    <div class="col-md-5">
                        <div class="card card-primary card-outline">
                            <div class="card-header">
                                {{ $ticket->subject }}
                                <span class="badge badge-@include('projects.tickets.partials.colour', ['status' => $ticket->status]) ml-2" style='font-size:14px;'>@include('projects.tickets.partials.status', ['status' => $ticket->status])</span>
                                @if($ticket->overdue)<span class="badge badge-danger ml-1" style="font-size:14px;">Overdue</span>@endif
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12 col-sm-4">
                                        <div class="info-box bg-light">
                                            <div class="info-box-content">
                                                <span class="info-box-text text-center text-muted">Due date</span>
                                                <span class="info-box-number text-center text-muted mb-0"><span class="badge badge-{{ $ticket->overdue ? 'danger' : 'secondary' }}">{{ $ticket->due_date->format('d.m.Y') }}</span></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-4">
                                        <div class="info-box bg-light">
                                            <div class="info-box-content">
                                                <span class="info-box-text text-center text-muted">Created at</span>
                                                <span class="info-box-number text-center text-muted mb-0"><span class="badge badge-secondary">{{ $ticket->created_at->format('d.m.Y') }}</span></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-4">
                                        <div class="info-box bg-light">
                                            <div class="info-box-content">
                                                <span class="info-box-text text-center text-muted">Status</span>
                                                <span class="info-box-number text-center text-muted mb-0"><span class="badge badge-@include('projects.tickets.partials.colour', ['status' => $ticket->status])">@include('projects.tickets.partials.status', ['status' => $ticket->status])</span></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <span class="d-block">Project: <b><a href="{{ route('projects.show', $ticket->project->id) }}">{{ $ticket->project->name }}</a></b></span>
                                <span class="d-block">Client: <b><a href="{{ route('clients.show', $ticket->project->client->id) }}">{{ $ticket->project->client->name }}</a></b></span>
                                <span class="d-block">Reporter: <b><a href="{{ route('users.show', $ticket->reporter->id) }}">{{ $ticket->reporter->full_name }}</a></b></span>
                                @if($ticket->assignee_id)<span class="d-block">Assigned: <b><a href="{{ route('users.show', $ticket->assignee->id) }}">{{ $ticket->assignee->full_name }}</a></b></span>@endif
                                <span class="d-block">Status: <b>@include('projects.tickets.partials.status', ['status' => $ticket->status])</b></span>
                                <span class="d-block">Priority: <b class="text-{{ $ticket->priority == App\Enums\TicketPriorityEnum::urgent ? 'danger' : 'body' }}">@include('projects.tickets.partials.priority', ['priority' => $ticket->priority])</b></span>
                                <span class="d-block">Type: <b>@include('projects.tickets.partials.type', ['type' => $ticket->type])</b></span>
                                <hr>
                                {!! $ticket->message !!}
                            </div>
                        </div>
                        <div class="card card-primary card-outline">
                            <div class="card-header">Activity Feed</div>
                            <div class="card-body">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-7">
                        <div class="card card-primary card-outline">
                            <div class="card-header">Files</div>
                            <div class="card-body">
                                @include('projects.tickets.files.upload', ['project' => $project, 'ticket' => $ticket])
                                <hr>
                                @include('files.list', ['files' => $ticket->files])
                            </div>
                        </div>
                        <div class="card card-primary card-outline">
                            <div class="card-header">Comments</div>
                            <div class="card-body">
                                @include('site.comments.list', ['comments' => $ticket->comments, 'comment' => $comment, 'createFormPartial' => 'projects.tickets.comments.create', 'editFormPartial' => 'projects.tickets.comments.edit'])
                            </div>
                        </div>
                    </div>
                </div> 
            </div>
        </section>
    </div>
@endsection