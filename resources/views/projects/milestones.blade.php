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
                    <li class="nav-item"><a class="nav-link active" href="{{ route('projects.milestones', $project->id) }}">Milestones</a></li>
                </ul>
            </div>

            <div class="card card-primary card-outline rounded-0">
                <div class="card-header"><a href="{{ route('milestones.create', ['project' => $project->id]) }}" class="bn btn-primary btn-sm"><i class="fas fa-plus mr-1"></i>Create</a></div>
                <div class="card-body">
                    <input type="hidden" id="milestoneform-message" value="{{ Session::get('message') }}">
                    <input type="hidden" id="milestoneform-message-type" value="{{ Session::get('type') }}">
                    <div class="table-responsive">
                        <table id="{{ count($project->milestones) > 0 ? 'milestones-table' : '' }}" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Owner</th>
                                    <th>Progress</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($project->milestones as $milestone)
                                    <tr>
                                        <td><a href="{{ route('milestones.detail', ['project' => $project->id, 'milestone' => $milestone->id]) }}">{{ $milestone->name }}</a></td>
                                        <td>{{ $milestone->owner->name }} {{ $milestone->owner->surname }}</td>
                                        <td>
                                            <div class="progress progress-sm">
                                                <div class="progress-bar bg-primary" role="progressbar" aria-valuenow="{{ $milestone->progress * 100 }}" aria-valuemin="0" aria-valuemax="100" style="width: {{ $milestone->progress * 100 }}%"></div>
                                            </div>
                                            <small>{{ $milestone->progress * 100 }}% Complete ({{ $milestone->tasksCompleted->count() }}/{{ $milestone->tasks->count() }})</small>
                                        </td>
                                        <td>{{ $milestone->start_date->format('d.m.Y') }}</td>
                                        <td>{{ $milestone->end_date->format('d.m.Y') }}</td>
                                        <td>
                                            <a href="{{ route('milestones.edit', ['project' => $project->id, 'milestone' => $milestone->id]) }}" class="btn btn-sm btn-dark" href=""><i class="fas fa-pencil-alt"></i></a>
                                            <a href="{{ route('milestones.detail', ['project' => $project->id, 'milestone' => $milestone->id]) }}" class="btn btn-sm btn-info" href=""><i class="fas fa-eye"></i></a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">No milestones were found!</td>
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
            $("#milestones-table").DataTable();

            if($('#milestoneform-message').val()) {
                if($('#milestoneform-message-type').val() == "success") {
                    toastr.success($('#milestoneform-message').val());
                } else if($('#milestoneform-message-type').val() == "info") {
                    toastr.info($('#milestoneform-message').val());
                } else {
                    toastr.error($('#milestoneform-message').val());            
                }
            }; 

            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>
@endsection