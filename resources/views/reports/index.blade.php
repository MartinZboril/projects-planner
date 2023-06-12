@extends('layouts.master')

@section('title', __('pages.title.report'))

@section('content')
    <div class="content-wrapper">
        <!-- Content Header -->
        <div class="p-3 mb-3" style="background-color:white;">
            <a href="{{ route('dashboard.index') }}" class="btn btn-sm btn-primary text-white"><i class="fas fa-caret-left mr-1"></i>Back</a>
        </div>
        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="card card-primary card-outline">
                    <div class="card-header">Available reports</div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <x-report.ui.info-box title="Projects" icon="fas fa-clock" :detail-route="route('reports.projects')" />               
                            </div>
                            <div class="col-md-3">
                                <x-report.ui.info-box title="Tasks" icon="fas fa-tasks" :detail-route="route('reports.tasks')" />              
                            </div>
                            <div class="col-md-3">
                                <x-report.ui.info-box title="Tickets" icon="fas fa-life-ring" :detail-route="route('reports.tickets')" />                
                            </div>
                            <div class="col-md-3">
                                <x-report.ui.info-box title="Milestones" icon="fas fa-calendar-alt" :detail-route="route('reports.milestones')" />               
                            </div>
                            <div class="col-md-3">
                                <x-report.ui.info-box title="Timesheets" icon="fas fa-stopwatch" :detail-route="route('reports.timesheets')" />               
                            </div>
                        </div>
                    </div>
                </div>            
            </div>
        </section>
    </div>
@endsection