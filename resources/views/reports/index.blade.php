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
                        <!-- Content -->
                        <div class="row">
                            <div class="col-md-3">
                                <div class="info-box">
                                    <span class="info-box-icon bg-primary"><a href="{{ route('reports.projects') }}"><i class="fas fa-clock"></i></a></span>                            
                                    <div class="info-box-content">
                                        <a href="{{ route('reports.projects') }}"><span class="info-box-number">Projects</span></a>
                                        <a href="{{ route('reports.projects') }}"><span class="info-box-text">View more</span></a>
                                    </div>
                                </div>                
                            </div>
                            <div class="col-md-3">
                                <div class="info-box">
                                    <span class="info-box-icon bg-primary"><a href="{{ route('reports.tasks') }}"><i class="fas fa-tasks"></i></a></span>                            
                                    <div class="info-box-content">
                                        <a href="{{ route('reports.tasks') }}"><span class="info-box-number">Tasks</span></a>
                                        <a href="{{ route('reports.tasks') }}"><span class="info-box-text">View more</span></a>
                                    </div>
                                </div>                
                            </div>
                            <div class="col-md-3">
                                <div class="info-box">
                                    <span class="info-box-icon bg-primary"><a href="{{ route('reports.tickets') }}"><i class="fas fa-life-ring"></i></a></span>                            
                                    <div class="info-box-content">
                                        <a href="{{ route('reports.tickets') }}"><span class="info-box-number">Tickets</span></a>
                                        <a href="{{ route('reports.tickets') }}"><span class="info-box-text">View more</span></a>
                                    </div>
                                </div>                
                            </div>
                            <div class="col-md-3">
                                <div class="info-box">
                                    <span class="info-box-icon bg-primary"><a href="{{ route('reports.milestones') }}"><i class="fas fa-calendar-alt"></i></a></span>                            
                                    <div class="info-box-content">
                                        <a href="{{ route('reports.milestones') }}"><span class="info-box-number">Milestones</span></a>
                                        <a href="{{ route('reports.milestones') }}"><span class="info-box-text">View more</span></a>
                                    </div>
                                </div>                
                            </div>
                            <div class="col-md-3">
                                <div class="info-box">
                                    <span class="info-box-icon bg-primary"><a href="{{ route('reports.timesheets') }}"><i class="fas fa-stopwatch"></i></a></span>                            
                                    <div class="info-box-content">
                                        <a href="{{ route('reports.timesheets') }}"><span class="info-box-number">Timesheets</span></a>
                                        <a href="{{ route('reports.timesheets') }}"><span class="info-box-text">View more</span></a>
                                    </div>
                                </div>                
                            </div>
                        </div>
                    </div>
                </div>            
            </div>
        </section>
    </div>
@endsection