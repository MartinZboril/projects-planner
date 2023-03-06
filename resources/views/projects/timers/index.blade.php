@extends('layouts.master', ['datatables' => true, 'toaster' => true])

@section('title', __('pages.title.project'))

@section('content')
    <div class="content-wrapper">
        <!-- Content Header -->
        <div class="p-3 mb-3" style="background-color:white;">
            @include('projects.partials.actions')
        </div>
        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="card-header p-0 pb-2 mb-2">
                    @include('projects.partials.header', ['active' => 'timesheets'])
                </div>          
                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <a href="{{ route('projects.timers.create', $project) }}" class="btn-sm btn-primary"><i class="fas fa-plus mr-1"></i>Create</a>
                        <a href="{{ route('reports.timesheets') }}" class="btn-sm btn-primary"><i class="fas fa-chart-line mr-1"></i>Report</a>
                    </div>
                    <div class="card-body">
                        <x-site.flash-messages :message="Session::get('message')" :type="Session::get('type')" />
                        <x-timer.table table-id="timesheets-table" type="projects" :project-id="$project->id" />       
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection