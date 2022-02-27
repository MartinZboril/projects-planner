@extends('layouts.master')

@section('styles')
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
            <input type="hidden" id="projectform-message" value="{{ Session::get('message') }}">
            <input type="hidden" id="projectform-message-type" value="{{ Session::get('type') }}">

            <div class="row">
                <div class="col-md-5">
                    <div class="card card-primary card-outline rounded-0">
                        <div class="card-header">Project</div>
                        <div class="card-body">
                            <div class="text-muted">
                                <p class="text-sm">Name
                                    <b class="d-block ml-2">{{ $project->name }}</b>
                                </p>
                                <hr>
                                <p class="text-sm">Client
                                    <b class="d-block ml-2">{{ $project->client->name }}</b>
                                </p>
                                <hr>
                                <p class="text-sm">Information
                                    <span class="d-block ml-2">Start date: <b>{{ $project->start_date->format('d.m.Y') }}</b></span>
                                    <span class="d-block ml-2">Due date: <b>{{ $project->due_date->format('d.m.Y') }}</b></span>
                                    <span class="d-block ml-2">Deadline: <b>{{ $project->deadline }}</b></span>
                                </p>
                                <p class="text-sm">Cost
                                    <span class="d-block ml-2">Est. Hours: <b>{{ $project->estimated_hours }} Hours</b></span>
                                    <span class="d-block ml-2">Remaining Hours: <b>{{ $project->remaining_hours }} Hours</b></span>
                                    <span class="d-block ml-2">Budget: <b>{{ number_format($project->budget, 2) }}</b></span>
                                    <span class="d-block ml-2">Used Budget: <b>{{ number_format($project->used_budget, 2) }}</b></span>
                                </p>
                                <hr>
                                <p class="text-sm">Description
                                    <b class="d-block ml-2">{!! $project->description !!}</b>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-7">
                    <div class="card card-primary card-outline rounded-0">
                        <div class="card-header">Activity Feed</div>
                        <div class="card-body">
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
    <script src="{{ asset('plugins/toastr/toastr.min.js' ) }}"></script>

    <script>
        $(function () {
            if($('#projectform-message').val()) {
                if($('#projectform-message-type').val() == "success") {
                    toastr.success($('#projectform-message').val());
                } else if($('#projectform-message-type').val() == "info") {
                    toastr.info($('#projectform-message').val());
                } else {
                    toastr.error($('#projectform-message').val());            
                }
            }; 
        });
    </script>
@endsection