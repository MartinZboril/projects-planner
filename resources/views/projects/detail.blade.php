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
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <input type="hidden" id="projectform-message" value="{{ Session::get('message') }}">
            <input type="hidden" id="projectform-message-type" value="{{ Session::get('type') }}">

            <div class="card-header p-0 pb-2 mb-2">
                <ul class="nav nav-pills">
                    <li class="nav-item"><a class="nav-link active" href="{{ route('projects.detail', $project->id) }}">Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('projects.tasks', $project->id) }}">Tasks</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('projects.kanban', $project->id) }}">Kanban</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('projects.milestones', $project->id) }}">Milestones</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('projects.timesheets', $project->id) }}">Timesheets</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('projects.tickets', $project->id) }}">Tickets</a></li>
                </ul>
            </div>

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
                                <p class="text-sm">Team
                                    <ul class="list-group list-group-flush ml-1">
                                        @foreach ($project->team as $user)
                                            <li class="list-group-item">
                                                <div class="user-block">
                                                    <img class="img-circle" src="{{ asset('dist/img/user.png') }}" alt="User Image" style="width:35px;height:35px;">
                                                    <span class="username">
                                                    {{ $user->name }} {{ $user->surname }}
                                                    </span>
                                                    <span class="description">Member</span>
                                                </div> 
                                                <!-- <a class="float-right"><i class="fas fa-trash-alt"></i></a> -->
                                            </li>
                                        @endforeach
                                    </ul>
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

@foreach (Auth::User()->rates as $rate)
    @include('timers.forms.start', ['id' => 'start-working-on-project-with-rate-' . $rate->id, 'projectId' => $project->id, 'rateId' => $rate->id])            
@endforeach

@if(Auth::User()->activeTimers->contains('project_id', $project->id))
    @include('timers.forms.stop', ['id' => 'stop-working-on-project', 'projectId' => Auth::User()->activeTimers->firstWhere('project_id', $project->id)->id])            
@endif

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