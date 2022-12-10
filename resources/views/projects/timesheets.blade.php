@extends('layouts.master', ['datatables' => true, 'toaster' => true])

@section('title', __('pages.title.project'))

@section('content')
    <div class="content-wrapper">
        <!-- Content Header -->
        <div class="p-3 mb-3" style="background-color:white;">
            @include('projects.partials.action', ['project' => $project])
        </div>
        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="card-header p-0 pb-2 mb-2">
                    @include('projects.partials.header', ['active' => 'timesheets'])
                </div>          
                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <a href="{{ route('projects.timers.create', ['project' => $project->id]) }}" class="btn-sm btn-primary"><i class="fas fa-plus mr-1"></i>Create</a>
                        <a href="{{ route('reports.timesheets') }}" class="btn-sm btn-primary"><i class="fas fa-chart-line mr-1"></i>Report</a>
                    </div>
                    <div class="card-body">
                        <!-- Message -->
                        @include('site.partials.message', ['message' => Session::get('message'), 'type' => Session::get('type')])
                        <!-- Content -->
                        @include('timers.partials.table', ['timers' => $project->timers, 'display' => []])       
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@push('scripts')
    <script>
        $(function () {
            $("#timesheets-table").DataTable();
            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>
@endpush