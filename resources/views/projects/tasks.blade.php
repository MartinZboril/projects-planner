@extends('layouts.master')

@section('title', __('pages.title.project'))

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
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="card-header p-0 pb-2 mb-2">
                @include('projects.partials.header', ['active' => 'task'])
            </div>
            <div class="card card-primary card-outline rounded-0">
                <div class="card-header"><a href="{{ route('projects.task.create', ['project' => $project->id]) }}" class="bn btn-primary btn-sm"><i class="fas fa-plus mr-1"></i>Create</a></div>
                <div class="card-body">
                    <!-- Message -->
                    @include('site.message', ['message' => Session::get('message'), 'type' => Session::get('type')])
                    <!-- Content -->
                    <div class="table-responsive">
                        <table id="@if(count($project->tasks) > 0){{ 'tasks-table' }}@endif" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>User</th>
                                    <th>Due Date</th>
                                    <th>Milestone</th>
                                    <th>Status</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($project->tasks as $task)
                                    <tr>
                                        <td><a href="{{ route('projects.task.detail', ['project' => $project->id, 'task' => $task->id]) }}">{{ $task->name }}</a></td>
                                        <td><img class="img-circle" src="{{ asset('dist/img/user.png') }}" alt="User Image" style="width:35px;height:35px;" data-toggle="tooltip" title="{{ $task->user->name }} {{ $task->user->surname }}"></td>
                                        <td>{{ $task->due_date->format('d.m.Y') }}</td>
                                        <td>{{ $task->milestone ? $task->milestone->name : '-' }}</td>
                                        <td>{{ $task->status->name }}</td>
                                        <td>
                                            <a class="btn btn-sm btn-dark" href="{{ route('projects.task.edit', ['project' => $project->id, 'task' => $task->id]) }}"><i class="fas fa-pencil-alt"></i></a>
                                            <a class="btn btn-sm btn-info" href="{{ route('projects.task.detail', ['project' => $project->id, 'task' => $task->id]) }}"><i class="fas fa-eye"></i></a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="100%" class="text-center">No tasks were found!</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>  
                    </div>        
                </div>
            </div>
        </div>
    </section>
</div>

@include('projects.partials.timers')

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
    <script src="{{ asset('plugins/toastr/toastr.min.js') }}"></script>
    <script src="{{ asset('js/toastr.js') }}"></script>
    <!-- Custom -->
    <script>
        $(function () {
            $("#tasks-table").DataTable();
            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>
@endpush