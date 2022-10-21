@extends('layouts.master')

@section('styles')
  <link rel="stylesheet" href="{{ asset('plugins/toastr/toastr.css') }}">
@endsection

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="p-3 rounded-0 mb-3" style="background-color:white;">
        <a href="{{ route('projects.tickets', $project->id) }}" class="btn btn-sm btn-primary text-white"><i class="fas fa-caret-left mr-1"></i>Back</a>
        <a href="{{ route('projects.ticket.edit', ['project' => $project->id, 'ticket' => $ticket->id]) }}" class="btn btn-sm btn-primary text-white"><i class="fas fa-pencil-alt mr-1"></i>Edit</a>
        @if (!$ticket->is_convert && $ticket->assignee_id && $ticket->status != 2 && $ticket->status != 3)
            <a href="#" class="btn btn-sm btn-primary" onclick="event.preventDefault(); document.getElementById('convert-ticket-to-task-form').submit();"><i class="fas fa-tasks mr-1"></i> Convert to task</a>
        @endif
        @if ($ticket->status == 1)
            <a href="#" class="btn btn-sm btn-success" onclick="event.preventDefault(); document.getElementById('close-ticket-form').submit();"><i class="fas fa-check mr-1"></i> Close</a>
        @elseif ($ticket->status == 2 || $ticket->status == 3)
            <a href="#" class="btn btn-sm btn-info" onclick="event.preventDefault(); document.getElementById('open-ticket-form').submit();"><i class="fas fa-bell mr-1"></i> Open</a>
        @endif
        @if ($ticket->status != 2 && $ticket->status != 3)
            <a href="#" class="btn btn-sm btn-primary" onclick="event.preventDefault(); document.getElementById('archive-ticket-form').submit();"><i class="fas fa-archive"></i></a>
        @endif
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <input type="hidden" id="ticketform-message" value="{{ Session::get('message') }}">
            <input type="hidden" id="ticketform-message-type" value="{{ Session::get('type') }}">

            <div class="row">
                <div class="col-md-5">
                    <div class="card card-primary card-outline rounded-0">
                        <div class="card-header">{{ $ticket->subject }}</div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12 col-sm-4">
                                    <div class="info-box bg-light">
                                        <div class="info-box-content">
                                            <span class="info-box-text text-center text-muted">Due date</span>
                                            <span class="info-box-number text-center text-muted mb-0"><span class="badge badge-danger">{{ $ticket->due_date->format('d.m.Y') }}</span></span>
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
                                            <span class="info-box-number text-center text-muted mb-0"><span class="badge badge-info">{{ $ticket->status == 1 ? 'Open' : ($ticket->status == 2 ? 'Closed' : ($ticket->status == 3 ? 'Archived' : $ticket->status)) }}</span></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <span class="d-block">Project: <b>{{ $ticket->project->name }}</b></span>
                            <span class="d-block">Client: <b>{{ $ticket->project->client->name }}</b></span>
                            <span class="d-block">Reporter: <b>{{ $ticket->reporter->name }} {{ $ticket->reporter->surname }}</b></span>
                            @if ($ticket->assignee)
                                <span class="d-block">Author: <b>{{ $ticket->assignee->name }} {{ $ticket->assignee->surname }}</b></span>
                            @endif
                            <span class="d-block">Status: <b>{{ $ticket->status == 1 ? 'Open' : ($ticket->status == 2 ? 'Closed' : ($ticket->status == 3 ? 'Archived' : $ticket->status)) }}</b></span>
                            <span class="d-block">Priority: <b>{{ $ticket->priority == 1 ? 'Error' : ($ticket->priority == 2 ? 'Inovation' : ($ticket->priority == 3 ? 'Help' : ($ticket->priority == 4 ? 'Other' : $ticket->priority))) }}</b></span>
                            <span class="d-block">Type: <b>{{ $ticket->type == 1 ? 'Low' : ($ticket->type == 2 ? 'Medium' : ($ticket->type == 3 ? 'High' : ($ticket->type == 4 ? 'Urgent' : $ticket->type))) }}</b></span>
                            <hr>
                            {!! $ticket->message !!}
                        </div>
                    </div>
                </div>
                <div class="col-md-7">
                    <div class="card card-primary card-outline rounded-0">
                        <div class="card-header">Activity Feed</div>
                        <div class="card-body">
                        </div>
                    </div>
                </div>
            </div>         
        </div>
    </section>
<!-- /.content -->
</div>

<form id="open-ticket-form" action="{{ route('tickets.change', $ticket->id) }}" method="POST" class="hidden">
    @csrf
    @method('PATCH')
    
    <input type="hidden" name="status" value="1">
    <input type="hidden" name="project_redirect" value="1">
</form>

<form id="close-ticket-form" action="{{ route('tickets.change', $ticket->id) }}" method="POST" class="hidden">
    @csrf
    @method('PATCH')

    <input type="hidden" name="status" value="2">
    <input type="hidden" name="project_redirect" value="1">
</form>

<form id="archive-ticket-form" action="{{ route('tickets.change', $ticket->id) }}" method="POST" class="hidden">
    @csrf
    @method('PATCH')
    
    <input type="hidden" name="status" value="3">
    <input type="hidden" name="project_redirect" value="1">
</form>

<form id="convert-ticket-to-task-form" action="{{ route('tickets.convert', $ticket->id) }}" method="POST" class="hidden">
    @csrf
    @method('PATCH')
    
    <input type="hidden" name="project_redirect" value="1">
</form>

@endsection

@section('scripts')
    <script src="{{ asset('plugins/toastr/toastr.min.js' ) }}"></script>

    <script>
        $(function () {
            if($('#ticketform-message').val()) {
                if($('#ticketform-message-type').val() == "success") {
                    toastr.success($('#ticketform-message').val());
                } else if($('#ticketform-message-type').val() == "info") {
                    toastr.info($('#ticketform-message').val());
                } else {
                    toastr.error($('#ticketform-message').val());            
                }
            }; 
        });
    </script>
@endsection