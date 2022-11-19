@extends('layouts.master')

@section('title', __('pages.title.milestone'))

@push('styles')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/toastr/toastr.css') }}">
@endpush

@section('content')
<div class="content-wrapper">
    <!-- Content Header -->
    <div class="p-3 rounded-0 mb-3" style="background-color:white;">
        <a href="{{ route('projects.milestones', $project->id) }}" class="btn btn-sm btn-primary text-white"><i class="fas fa-caret-left mr-1"></i>Back</a>
        <a href="{{ route('milestones.edit', ['project' => $project->id, 'milestone' => $milestone->id]) }}" class="btn btn-sm btn-primary text-white"><i class="fas fa-pencil-alt mr-1"></i>Edit</a>
    </div>
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <!-- Message -->
            @include('site.partials.message', ['message' => Session::get('message'), 'type' => Session::get('type')])
            <!-- Content -->
            <div class="row">
                <div class="col-md-5">
                    <div class="card card-primary card-outline rounded-0">
                        <div class="card-header">
                            {{ $milestone->name }}
                            <span class="badge badge-{{ $milestone->progress == 1 ? 'success' : 'warning' }} ml-1" style="font-size:14px;">{{ $milestone->progress == 1 ? 'Completed' : 'In progress' }}</span>
                            @if($milestone->overdue)<span class="badge badge-danger ml-1" style="font-size:14px;">Overdue</span>@endif
                        </div>
                        <div class="card-body">
                            <span class="d-block">Project: <b><a href="{{ route('projects.detail', $milestone->project->id) }}">{{ $milestone->project->name }}</a></b></span>
                            <span class="d-block">User: <b><a href="{{ route('users.detail', $milestone->owner) }}">{{ $milestone->owner->full_name }}</a></b></span>
                            <span class="d-block">Start date: <b>{{ $milestone->start_date->format('d.m.Y') }}</b></span>
                            <span class="d-block">End date: <b>{{ $milestone->end_date->format('d.m.Y') }}</b></span>
                            <span class="d-block">Tasks: <b>{{ $milestone->tasksCompleted->count() }}/{{ $milestone->tasks->count() }}</b><span class="badge badge-{{ $milestone->progress == 1 ? 'success' : 'warning' }} ml-1">{{ $milestone->progress * 100 }} % Complete</span></span>
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
                            @include('tasks.partials.table', ['tasks' => $milestone->tasks, 'display' => [], 'redirect' => 'project'])       
                        </div>
                    </div>
                </div>
            </div>         
        </div>
    </section>
</div>
@endsection

@push('scripts')
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
    <!-- Toastr -->
    <script src="{{ asset('plugins/toastr/toastr.min.js' ) }}"></script>
    <script src="{{ asset('js/toastr.js') }}"></script>
    <!-- Custom -->
    <script>
        $(function () {
            $("#tasks-table").DataTable();
        });
    </script>
@endpush