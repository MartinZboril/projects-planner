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
        <a href="{{ route('projects.milestones', $project->id) }}" class="btn btn-sm btn-primary text-white"><i class="fas fa-caret-left mr-1"></i>Back</a>
        <a href="{{ route('milestones.edit', ['project' => $project->id, 'milestone' => $milestone->id]) }}" class="btn btn-sm btn-primary text-white"><i class="fas fa-pencil-alt mr-1"></i>Edit</a>
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <input type="hidden" id="milestoneform-message" value="{{ Session::get('message') }}">
            <input type="hidden" id="milestoneform-message-type" value="{{ Session::get('type') }}">

            <div class="row">
                <div class="col-md-5">
                    <div class="card card-primary card-outline rounded-0">
                        <div class="card-header">{{ $milestone->name }}</div>
                        <div class="card-body">
                            <span class="d-block">Project: <b>{{ $milestone->project->name }}</b></span>
                            <span class="d-block">User: <b>{{ $milestone->owner->name }} {{ $milestone->owner->surname }}</b></span>
                            <span class="d-block">Start date: <b>{{ $milestone->start_date->format('d.m.Y') }}</b></span>
                            <span class="d-block">End date: <b>{{ $milestone->end_date->format('d.m.Y') }}</b></span>
                            <span class="d-block">Tasks: <b>{{ $milestone->progress * 100 }}% Complete ({{ $milestone->tasksCompleted->count() }}/{{ $milestone->tasks->count() }})</b></span>
                            <hr>
                            {!! $milestone->description !!}
                        </div>
                    </div>
                    <div class="card card-primary card-outline rounded-0">
                        <div class="card-header">Activity Feed</div>
                        <div class="card-body">
                        </div>
                    </div>
                </div>
                <div class="col-md-7">
                    <div class="card card-primary card-outline rounded-0">
                        <div class="card-header">Tasks</div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="{{ count($milestone->tasks) > 0 ? 'tasks-table' : '' }}" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Project</th>
                                            <th>User</th>
                                            <th>Due Date</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($milestone->tasks as $task)
                                            <tr>
                                                <td><a href="{{ route('tasks.detail', $task->id) }}">{{ $task->name }}</a></td>
                                                <td>{{ $task->project->name }}</td>
                                                <td><img class="img-circle" src="{{ asset('dist/img/user.png') }}" alt="User Image" style="width:35px;height:35px;" data-toggle="tooltip" title="{{ $task->user->name }} {{ $task->user->surname }}"></td>
                                                <td>{{ $task->due_date->format('d.m.Y') }}</td>
                                                <td>{{ $task->is_stopped ? 'Stopped' : ($task->is_returned ? 'Returned' : $task->status->name) }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center">No tasks were found!</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>  
                            </div>
                        </div>
                    </div>
                </div>
            </div>         
        </div>
    </section>
<!-- /.content -->
</div>
@endsection

@section('scripts')
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
    <script src="{{ asset('plugins/toastr/toastr.min.js' ) }}"></script>

    <script>
        $(function () {
            $("#tasks-table").DataTable();

            if($('#milestoneform-message').val()) {
                if($('#milestoneform-message-type').val() == "success") {
                    toastr.success($('#milestoneform-message').val());
                } else if($('#milestoneform-message-type').val() == "info") {
                    toastr.info($('#milestoneform-message').val());
                } else {
                    toastr.error($('#milestoneform-message').val());            
                }
            }; 
        });
    </script>
@endsection