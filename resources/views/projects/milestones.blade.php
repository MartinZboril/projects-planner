@extends('layouts.master')

@section('title', __('pages.title.project'))

@push('styles')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
@endpush

@section('content')
<div class="content-wrapper">
    <!-- Content Header -->
    <div class="p-3 rounded-0 mb-3" style="background-color:white;">
        @include('projects.partials.action', ['project' => $project])
    </div>
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="card-header p-0 pb-2 mb-2">
                @include('projects.partials.header', ['active' => 'milestone'])
            </div>
            <div class="card card-primary card-outline rounded-0">
                <div class="card-header"><a href="{{ route('milestones.create', ['project' => $project->id]) }}" class="bn btn-primary btn-sm"><i class="fas fa-plus mr-1"></i>Create</a></div>
                <div class="card-body">
                    <!-- Message -->
                    @include('site.partials.message', ['message' => Session::get('message'), 'type' => Session::get('type')])
                    <!-- Content -->
                    <div class="table-responsive">
                        <table id="@if(count($project->milestones) > 0){{ 'milestones-table' }}@endif" class="table table-bordered table-striped">
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
                                        <td>{{ $milestone->owner->full_name }}</td>
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
                                        <td colspan="100%" class="text-center">No milestones were found!</td>
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

@include('projects.partials.forms', ['project' => $project])

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
    <script src="{{ asset('js/toastr.js') }}"></script>
    <!-- Custom -->
    <script>
        $(function () {
            $("#milestones-table").DataTable();
            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>
@endpush