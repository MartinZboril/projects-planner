@extends('layouts.master')

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
            <a href="#" class="btn btn-sm btn-success" onclick="event.preventDefault(); document.getElementById('start-working-on-project').submit();"><i class="fas fa-play mr-1"></i>Start</a>
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
                    <li class="nav-item"><a class="nav-link active" href="{{ route('projects.timesheets', $project->id) }}">Timesheets</a></li>
                </ul>
            </div>

            <div class="card card-primary card-outline rounded-0">
                <div class="card-header"><a href="{{ route('timers.create', ['project' => $project->id]) }}" class="bn btn-primary btn-sm"><i class="fas fa-plus mr-1"></i>Create</a></div>
                <div class="card-body">
                    <input type="hidden" id="timesheetform-message" value="{{ Session::get('message') }}">
                    <input type="hidden" id="timesheetform-message-type" value="{{ Session::get('type') }}">
                    <div class="table-responsive">
                        <table id="{{ count($project->timers) > 0 ? 'timesheets-table' : '' }}" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>User</th>
                                    <th>Total time (Hours)</th>
                                    <th>Start</th>
                                    <th>Stop</th>
                                    <th>Date</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($project->timers as $timer)
                                    <tr>
                                        <td>{{ $timer->user->name }} {{ $timer->user->surname }}</td>
                                        <td>{{ (!$timer->until) ? 'N/A' : (($timer->total_time) ? $timer->total_time : 0) }}</td>
                                        <td>{{ $timer->since->format('d.m.Y H:i') }}</td>
                                        <td>{{ ($timer->until) ? $timer->until->format('d.m.Y H:i') : 'N/A' }}</td>
                                        <td>{{ $timer->since->format('d.m.Y') }}</td>
                                        <td>
                                            @if($timer->until)
                                                <a href="{{ route('timers.edit', ['project' => $project->id, 'timer' => $timer->id]) }}" class="btn btn-sm btn-dark" href=""><i class="fas fa-pencil-alt"></i></a>
                                            @else
                                                N/A
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">No timesheets were found!</td>
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

<form id="start-working-on-project" action="{{ route('projects.timer.start', ['project' => $project->id]) }}" method="POST" class="hidden">
    @csrf
</form>

@if(Auth::User()->activeTimers->contains('project_id', $project->id))
    <form id="stop-working-on-project" action="{{ route('projects.timer.stop', ['project' => $project->id, 'timer' => Auth::User()->activeTimers->firstWhere('project_id', $project->id)->id]) }}" method="POST" class="hidden">
        @csrf
    </form>
@endif
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
            $("#timesheets-table").DataTable();

            if($('#timesheetform-message').val()) {
                if($('#timesheetform-message-type').val() == "success") {
                    toastr.success($('#timesheetform-message').val());
                } else if($('#timesheetform-message-type').val() == "info") {
                    toastr.info($('#timesheetform-message').val());
                } else {
                    toastr.error($('#timesheetform-message').val());            
                }
            }; 

            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>
@endsection