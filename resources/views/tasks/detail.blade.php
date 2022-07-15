@extends('layouts.master')

@section('styles')
  <link rel="stylesheet" href="{{ asset('plugins/toastr/toastr.css') }}">
@endsection

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="p-3 rounded-0 mb-3" style="background-color:white;">
        <a href="{{ route('tasks.index') }}" class="btn btn-sm btn-primary text-white"><i class="fas fa-caret-left mr-1"></i>Back</a>
        <a href="{{ route('tasks.edit', $task->id) }}" class="btn btn-sm btn-primary text-white"><i class="fas fa-pencil-alt mr-1"></i>Edit</a>
        @if ($task->status->id == 1)
            <a href="#" class="btn btn-sm btn-info" onclick="event.preventDefault(); document.getElementById('start-working-on-task-form').submit();"><i class="fas fa-play mr-1"></i> Start</a>
        @elseif ($task->status->id == 2)
            <a href="#" class="btn btn-sm btn-success {{ ($task->is_stopped) ? 'disabled' : '' }}" onclick="event.preventDefault(); document.getElementById('complete-working-on-task-form').submit();"><i class="fas fa-check mr-1"></i> Complete</a>
            @if ($task->is_stopped)
                <a href="#" class="btn btn-sm btn-info" onclick="event.preventDefault(); document.getElementById('resume-working-on-task-form').submit();"><i class="fas fa-hourglass-start mr-1"></i> Resume</a>
            @else
                <a href="#" class="btn btn-sm btn-danger" onclick="event.preventDefault(); document.getElementById('stop-working-on-task-form').submit();"><i class="fas fa-stop mr-1"></i> Stop</a>
            @endif
        @endif
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <input type="hidden" id="taskform-message" value="{{ Session::get('message') }}">
            <input type="hidden" id="taskform-message-type" value="{{ Session::get('type') }}">

            <div class="row">
                <div class="col-md-5">
                    <div class="card card-primary card-outline rounded-0">
                        <div class="card-header">{{ $task->name }} {!! ($task->is_stopped) ? "<span class='badge badge-danger ml-2' style='font-size:14px;'>Stopped</span>"  : '' !!}</div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12 col-sm-4">
                                    <div class="info-box bg-light">
                                        <div class="info-box-content">
                                            <span class="info-box-text text-center text-muted">Due date</span>
                                            <span class="info-box-number text-center text-muted mb-0"><span class="badge badge-danger">{{ $task->due_date->format('d.m.Y') }}</span></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-4">
                                    <div class="info-box bg-light">
                                        <div class="info-box-content">
                                            <span class="info-box-text text-center text-muted">Start date</span>
                                            <span class="info-box-number text-center text-muted mb-0"><span class="badge badge-success">{{ $task->start_date->format('d.m.Y') }}</span></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-4">
                                    <div class="info-box bg-light">
                                        <div class="info-box-content">
                                            <span class="info-box-text text-center text-muted">Created at</span>
                                            <span class="info-box-number text-center text-muted mb-0"><span class="badge badge-secondary">{{ $task->created_at->format('d.m.Y') }}</span></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <span class="d-block">Project: <b>{{ $task->project->name }}</b></span>
                            <span class="d-block">Client: <b>{{ $task->project->client->name }}</b></span>
                            <span class="d-block">User: <b>{{ $task->user->name }} {{ $task->user->surname }}</b></span>
                            @if ($task->user->id != $task->author->id)
                                <span class="d-block">Author: <b>{{ $task->author->name }} {{ $task->author->surname }}</b></span>
                            @endif
                            <span class="d-block">Status: <b>{{ $task->status->name }}</b></span>
                            <hr>
                            {!! $task->description !!}
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

<form id="start-working-on-task-form" action="{{ route('tasks.start', $task->id) }}" method="POST" class="hidden">
    @csrf
    @method('PATCH')
</form>

<form id="complete-working-on-task-form" action="{{ route('tasks.complete', $task->id) }}" method="POST" class="hidden">
    @csrf
    @method('PATCH')
</form>

<form id="stop-working-on-task-form" action="{{ route('tasks.stop', $task->id) }}" method="POST" class="hidden">
    @csrf
    @method('PATCH')
</form>

<form id="resume-working-on-task-form" action="{{ route('tasks.resume', $task->id) }}" method="POST" class="hidden">
    @csrf
    @method('PATCH')
</form>
@endsection

@section('scripts')
    <script src="{{ asset('plugins/toastr/toastr.min.js' ) }}"></script>

    <script>
        $(function () {
            if($('#taskform-message').val()) {
                if($('#taskform-message-type').val() == "success") {
                    toastr.success($('#taskform-message').val());
                } else if($('#taskform-message-type').val() == "info") {
                    toastr.info($('#taskform-message').val());
                } else {
                    toastr.error($('#taskform-message').val());            
                }
            }; 
        });
    </script>
@endsection