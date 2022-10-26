@extends('layouts.master')

@section('title', __('pages.title.project'))

@section('styles')
    <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/toastr/toastr.css') }}">
@endsection

@section('content')
<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="p-3 rounded-0 mb-3" style="background-color:white;">
        <a href="{{ route('projects.index') }}" class="btn btn-sm btn-primary text-white"><i class="fas fa-caret-left mr-1"></i>Back</a>
        <a href="{{ route('projects.edit', $project->id) }}" class="btn btn-sm btn-primary text-white"><i class="fas fa-pencil-alt mr-1"></i>Edit</a>
        @if(Auth::User()->activeTimers->contains('project_id', $project->id))
            <a href="#" class="btn btn-sm btn-danger" onclick="event.preventDefault(); document.getElementById('stop-working-on-project').submit();"><i class="fas fa-stop mr-1"></i>Stop</a>
        @else
            <div class="btn-group">
                <button type="button" class="btn btn-success btn-sm dropdown-toggle" data-toggle="dropdown">
                    Start
                </button>
                <div class="dropdown-menu">
                    @foreach (Auth::User()->rates as $rate)
                        <a class="dropdown-item" href="#" onclick="event.preventDefault(); document.getElementById('start-working-on-project-with-rate-{{ $rate->id }}').submit();">{{ $rate->name }} ({{ $rate->value }})</a>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="card-header p-0 pb-2 mb-2">
                <ul class="nav nav-pills">
                    <li class="nav-item"><a class="nav-link" href="{{ route('projects.detail', $project->id) }}">Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('projects.tasks', $project->id) }}">Tasks</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('projects.kanban', $project->id) }}">Kanban</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('projects.milestones', $project->id) }}">Milestones</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('projects.timesheets', $project->id) }}">Timesheets</a></li>
                    <li class="nav-item"><a class="nav-link active" href="{{ route('projects.tickets', $project->id) }}">Tickets</a></li>
                </ul>
            </div>

            <div class="card card-primary card-outline rounded-0">
                <div class="card-header"><a href="{{ route('projects.ticket.create', ['project' => $project->id]) }}" class="bn btn-primary btn-sm"><i class="fas fa-plus mr-1"></i>Create</a></div>
                <div class="card-body">
                    <input type="hidden" id="ticketform-message" value="{{ Session::get('message') }}">
                    <input type="hidden" id="ticketform-message-type" value="{{ Session::get('type') }}">
       
                    <div class="table-responsive">
                        <table id="{{ count($project->tickets) > 0 ? 'tickets-table' : '' }}" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Subject</th>
                                    <th>Reporter</th>
                                    <th>Assignee</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                    <th>Type</th>
                                    <th>Priority</th>
                                    <th>Due Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($project->tickets as $ticket)
                                    <tr>
                                        <td><a href="{{ route('projects.ticket.detail', ['project' => $project->id, 'ticket' => $ticket->id]) }}">{{ $ticket->subject }}</a></td>
                                        <td><img class="img-circle" src="{{ asset('dist/img/user.png') }}" alt="User Image" style="width:35px;height:35px;" data-toggle="tooltip" title="{{ $ticket->reporter->name }} {{ $ticket->reporter->surname }}"></td>
                                        <td>
                                            @if($ticket->assignee)
                                                <img class="img-circle" src="{{ asset('dist/img/user.png') }}" alt="User Image" style="width:35px;height:35px;" data-toggle="tooltip" title="{{ $ticket->assignee->name }} {{ $ticket->assignee->surname }}">
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>{{ $ticket->created_at->format('d.m.Y') }}</td>
                                        <td>{{ $ticket->status == 1 ? 'Open' : ($ticket->status == 2 ? 'Closed' : ($ticket->status == 3 ? 'Archived' : $ticket->status)) }}</td>
                                        <td>{{ $ticket->type == 1 ? 'Error' : ($ticket->type == 2 ? 'Inovation' : ($ticket->type == 3 ? 'Help' : ($ticket->type == 4 ? 'Other' : $ticket->type))) }}</td>
                                        <td>{{ $ticket->priority == 1 ? 'Low' : ($ticket->priority == 2 ? 'Medium' : ($ticket->priority == 3 ? 'High' : ($ticket->priority == 4 ? 'Urgent' : $ticket->priority))) }}</td>
                                        <td>{{ $ticket->due_date->format('d.m.Y') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center">No tickets were found!</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>  
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- /.content -->
</div>

@endsection

@section('scripts')
    <!-- DataTables -->
    <script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('plugins/jszip/jszip.min.js') }}"></script>
    <script src="{{ asset('plugins/pdfmake/pdfmake.min.js') }}"></script>
    <script src="{{ asset('plugins/pdfmake/vfs_fonts.js') }}"></script>
    <script src="{{ asset('plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-buttons/js/buttons.print.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-buttons/js/buttons.colVis.min.js') }}"></script>
    <script src="{{ asset('plugins/toastr/toastr.min.js') }}"></script>

    <script>
        $(function () {
            $("#tickets-table").DataTable();

            if($('#ticketform-message').val()) {
                if($('#ticketform-message-type').val() == "success") {
                    toastr.success($('#ticketform-message').val());
                } else if($('#ticketform-message-type').val() == "info") {
                    toastr.info($('#ticketform-message').val());
                } else {
                    toastr.error($('#ticketform-message').val());            
                }
            }; 

            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>
@endsection