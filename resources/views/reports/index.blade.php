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
                                <x-report.ui.info-box title="Projects" :detail-route="route('reports.projects')" />               
                            </div>
                            <div class="col-md-3">
                                <x-report.ui.info-box title="Tasks" :detail-route="route('reports.tasks')" />              
                            </div>
                            <div class="col-md-3">
                                <x-report.ui.info-box title="Tickets" :detail-route="route('reports.tickets')" />                
                            </div>
                            <div class="col-md-3">
                                <x-report.ui.info-box title="Milestones" :detail-route="route('reports.milestones')" />               
                            </div>
                            <div class="col-md-3">
                                <x-report.ui.info-box title="Timesheets" :detail-route="route('reports.timesheets')" />               
                            </div>
                        </div>
                    </div>
                </div>            
            </div>
        </section>
    </div>
@endsection