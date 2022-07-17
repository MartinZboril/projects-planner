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
            <input type="hidden" id="kanban-message" value="{{ Session::get('message') }}">
            <input type="hidden" id="kanban-message-type" value="{{ Session::get('type') }}">

            <div class="card-header p-0 pb-2">
                <ul class="nav nav-pills">
                    <li class="nav-item"><a class="nav-link" href="{{ route('projects.detail', $project->id) }}">Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('projects.tasks', $project->id) }}">Tasks</a></li>
                    <li class="nav-item"><a class="nav-link active" href="{{ route('projects.kanban', $project->id) }}">Kanban</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('projects.milestones', $project->id) }}">Milestones</a></li>
                </ul>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="card card-info">
                            <div class="card-header">New</div>
                            <div class="card-body">
                                @forelse ($project->newTasks as $task)
                                    <div class="card card-info card-outline">
                                        <div class="card-header">
                                            <div class="card-title"><a href="{{ route('tasks.detail', $task->id) }}">{{ $task->name }}</a> {!! $task->is_stopped ? "<span class='badge badge-danger ml-2' style='font-size:14px;'>Stopped</span>" : ($task->is_returned ? "<span class='badge badge-danger ml-2' style='font-size:14px;'>Returned</span>" : '') !!}</div>
                                            <div class="card-tools">
                                                @if ($task->status->id == 1)
                                                    <a href="#" class="btn btn-sm btn-tool" onclick="event.preventDefault(); document.getElementById('start-working-on-task-{{ $task->id }}-form').submit();"><i class="fas fa-play" data-toggle="tooltip" data-placement="bottom" title="Start"></i></a>
                                                @elseif ($task->status->id == 2)
                                                    @if ($task->is_stopped)
                                                        <a href="#" class="btn btn-sm btn-tool" onclick="event.preventDefault(); document.getElementById('resume-working-on-task-{{ $task->id }}-form').submit();"><i class="fas fa-hourglass-start" data-toggle="tooltip" data-placement="bottom" title="Resume"></i></a>
                                                    @else
                                                        <a href="#" class="btn btn-sm btn-tool" onclick="event.preventDefault(); document.getElementById('complete-working-on-task-{{ $task->id }}-form').submit();"><i class="fas fa-check" data-toggle="tooltip" data-placement="bottom" title="Complete"></i></a>
                                                        <a href="#" class="btn btn-sm btn-tool" onclick="event.preventDefault(); document.getElementById('stop-working-on-task-{{ $task->id }}-form').submit();"><i class="fas fa-stop" data-toggle="tooltip" data-placement="bottom" title="Stop"></i></a>
                                                    @endif
                                                @else
                                                    <a href="#" class="btn btn-sm btn-tool" onclick="event.preventDefault(); document.getElementById('return-working-on-task-{{ $task->id }}-form').submit();"><i class="fas fa-undo" data-toggle="tooltip" data-placement="bottom" title="Return"></i></a>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <span class="d-block">Due date: <span class="btn btn-sm btn-outline-danger disabled mb-1" style="font-size:14px;">{{ $task->due_date->format('d.m.Y') }}</span></span>
                                            <span class="d-block">User: <b>{{ $task->user->name }} {{ $task->user->surname }}</b></span>
                                            @if ($task->user->id != $task->author->id)
                                                <span class="d-block">Author: <b>{{ $task->author->name }} {{ $task->author->surname }}</b></span>
                                            @endif
                                            @if ($task->milestone)
                                                <span class="d-block">Milestone: <b>{{ $task->milestone->name }}</b></span>
                                            @endif
                                        </div>
                                    </div>

                                    <form id="start-working-on-task-{{ $task->id }}-form" action="{{ route('projects.tasks.start', ['project' => $project->id, 'task' => $task->id]) }}" method="POST" class="hidden">
                                        @csrf
                                        @method('PATCH')
                                    </form>

                                    <form id="complete-working-on-task-{{ $task->id }}-form" action="{{ route('projects.tasks.complete', ['project' => $project->id, 'task' => $task->id]) }}" method="POST" class="hidden">
                                        @csrf
                                        @method('PATCH')
                                    </form>

                                    <form id="stop-working-on-task-{{ $task->id }}-form" action="{{ route('projects.tasks.stop', ['project' => $project->id, 'task' => $task->id]) }}" method="POST" class="hidden">
                                        @csrf
                                        @method('PATCH')
                                    </form>

                                    <form id="resume-working-on-task-{{ $task->id }}-form" action="{{ route('projects.tasks.resume', ['project' => $project->id, 'task' => $task->id]) }}" method="POST" class="hidden">
                                        @csrf
                                        @method('PATCH')
                                    </form>

                                    <form id="return-working-on-task-{{ $task->id }}-form" action="{{ route('projects.tasks.return', ['project' => $project->id, 'task' => $task->id]) }}" method="POST" class="hidden">
                                        @csrf
                                        @method('PATCH')
                                    </form>
                                @empty
                                    <div class="card">
                                        <div class="card-header">There are no tasks!</div>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card card-warning">
                            <div class="card-header">In Progress</div>
                            <div class="card-body">
                                @forelse ($project->inProgressTasks as $task)
                                    <div class="card card-warning card-outline">
                                        <div class="card-header">
                                            <div class="card-title"><a href="{{ route('tasks.detail', $task->id) }}">{{ $task->name }}</a> {!! $task->is_stopped ? "<span class='badge badge-danger ml-2' style='font-size:14px;'>Stopped</span>" : ($task->is_returned ? "<span class='badge badge-danger ml-2' style='font-size:14px;'>Returned</span>" : '') !!}</div>
                                            <div class="card-tools">
                                                @if ($task->status->id == 1)
                                                    <a href="#" class="btn btn-sm btn-tool" onclick="event.preventDefault(); document.getElementById('start-working-on-task-{{ $task->id }}-form').submit();"><i class="fas fa-play" data-toggle="tooltip" data-placement="bottom" title="Start"></i></a>
                                                @elseif ($task->status->id == 2)
                                                    @if ($task->is_stopped)
                                                        <a href="#" class="btn btn-sm btn-tool" onclick="event.preventDefault(); document.getElementById('resume-working-on-task-{{ $task->id }}-form').submit();"><i class="fas fa-hourglass-start" data-toggle="tooltip" data-placement="bottom" title="Resume"></i></a>
                                                    @else
                                                        <a href="#" class="btn btn-sm btn-tool" onclick="event.preventDefault(); document.getElementById('complete-working-on-task-{{ $task->id }}-form').submit();"><i class="fas fa-check" data-toggle="tooltip" data-placement="bottom" title="Complete"></i></a>
                                                        <a href="#" class="btn btn-sm btn-tool" onclick="event.preventDefault(); document.getElementById('stop-working-on-task-{{ $task->id }}-form').submit();"><i class="fas fa-stop" data-toggle="tooltip" data-placement="bottom" title="Stop"></i></a>
                                                    @endif
                                                @else
                                                    <a href="#" class="btn btn-sm btn-tool" onclick="event.preventDefault(); document.getElementById('return-working-on-task-{{ $task->id }}-form').submit();"><i class="fas fa-undo" data-toggle="tooltip" data-placement="bottom" title="Return"></i></a>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <span class="d-block">Due date: <span class="btn btn-sm btn-outline-danger disabled mb-1" style="font-size:14px;">{{ $task->due_date->format('d.m.Y') }}</span></span>
                                            <span class="d-block">User: <b>{{ $task->user->name }} {{ $task->user->surname }}</b></span>
                                            @if ($task->user->id != $task->author->id)
                                                <span class="d-block">Author: <b>{{ $task->author->name }} {{ $task->author->surname }}</b></span>
                                            @endif
                                            @if ($task->milestone)
                                                <span class="d-block">Milestone: <b>{{ $task->milestone->name }}</b></span>
                                            @endif
                                        </div>
                                    </div>

                                    <form id="start-working-on-task-{{ $task->id }}-form" action="{{ route('projects.tasks.start', ['project' => $project->id, 'task' => $task->id]) }}" method="POST" class="hidden">
                                        @csrf
                                        @method('PATCH')
                                    </form>

                                    <form id="complete-working-on-task-{{ $task->id }}-form" action="{{ route('projects.tasks.complete', ['project' => $project->id, 'task' => $task->id]) }}" method="POST" class="hidden">
                                        @csrf
                                        @method('PATCH')
                                    </form>

                                    <form id="stop-working-on-task-{{ $task->id }}-form" action="{{ route('projects.tasks.stop', ['project' => $project->id, 'task' => $task->id]) }}" method="POST" class="hidden">
                                        @csrf
                                        @method('PATCH')
                                    </form>

                                    <form id="resume-working-on-task-{{ $task->id }}-form" action="{{ route('projects.tasks.resume', ['project' => $project->id, 'task' => $task->id]) }}" method="POST" class="hidden">
                                        @csrf
                                        @method('PATCH')
                                    </form>

                                    <form id="return-working-on-task-{{ $task->id }}-form" action="{{ route('projects.tasks.return', ['project' => $project->id, 'task' => $task->id]) }}" method="POST" class="hidden">
                                        @csrf
                                        @method('PATCH')
                                    </form>
                                @empty
                                    <div class="card">
                                        <div class="card-header">There are no tasks!</div>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card card-success">
                            <div class="card-header">Completed</div>
                            <div class="card-body">
                                @forelse ($project->completedTasks as $task)
                                    <div class="card card-success card-outline">
                                        <div class="card-header">
                                            <div class="card-title"><a href="{{ route('tasks.detail', $task->id) }}">{{ $task->name }}</a> {!! $task->is_stopped ? "<span class='badge badge-danger ml-2' style='font-size:14px;'>Stopped</span>" : ($task->is_returned ? "<span class='badge badge-danger ml-2' style='font-size:14px;'>Returned</span>" : '') !!}</div>
                                            <div class="card-tools">
                                                @if ($task->status->id == 1)
                                                    <a href="#" class="btn btn-sm btn-tool" onclick="event.preventDefault(); document.getElementById('start-working-on-task-{{ $task->id }}-form').submit();"><i class="fas fa-play" data-toggle="tooltip" data-placement="bottom" title="Start"></i></a>
                                                @elseif ($task->status->id == 2)
                                                    @if ($task->is_stopped)
                                                        <a href="#" class="btn btn-sm btn-tool" onclick="event.preventDefault(); document.getElementById('resume-working-on-task-{{ $task->id }}-form').submit();"><i class="fas fa-hourglass-start" data-toggle="tooltip" data-placement="bottom" title="Resume"></i></a>
                                                    @else
                                                        <a href="#" class="btn btn-sm btn-tool" onclick="event.preventDefault(); document.getElementById('complete-working-on-task-{{ $task->id }}-form').submit();"><i class="fas fa-check" data-toggle="tooltip" data-placement="bottom" title="Complete"></i></a>
                                                        <a href="#" class="btn btn-sm btn-tool" onclick="event.preventDefault(); document.getElementById('stop-working-on-task-{{ $task->id }}-form').submit();"><i class="fas fa-stop" data-toggle="tooltip" data-placement="bottom" title="Stop"></i></a>
                                                    @endif
                                                @else
                                                    <a href="#" class="btn btn-sm btn-tool" onclick="event.preventDefault(); document.getElementById('return-working-on-task-{{ $task->id }}-form').submit();"><i class="fas fa-undo" data-toggle="tooltip" data-placement="bottom" title="Return"></i></a>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <span class="d-block">Due date: <span class="btn btn-sm btn-outline-danger disabled mb-1" style="font-size:14px;">{{ $task->due_date->format('d.m.Y') }}</span></span>
                                            <span class="d-block">User: <b>{{ $task->user->name }} {{ $task->user->surname }}</b></span>
                                            @if ($task->user->id != $task->author->id)
                                                <span class="d-block">Author: <b>{{ $task->author->name }} {{ $task->author->surname }}</b></span>
                                            @endif
                                            @if ($task->milestone)
                                                <span class="d-block">Milestone: <b>{{ $task->milestone->name }}</b></span>
                                            @endif
                                        </div>
                                    </div>

                                    <form id="start-working-on-task-{{ $task->id }}-form" action="{{ route('projects.tasks.start', ['project' => $project->id, 'task' => $task->id]) }}" method="POST" class="hidden">
                                        @csrf
                                        @method('PATCH')
                                    </form>

                                    <form id="complete-working-on-task-{{ $task->id }}-form" action="{{ route('projects.tasks.complete', ['project' => $project->id, 'task' => $task->id]) }}" method="POST" class="hidden">
                                        @csrf
                                        @method('PATCH')
                                    </form>

                                    <form id="stop-working-on-task-{{ $task->id }}-form" action="{{ route('projects.tasks.stop', ['project' => $project->id, 'task' => $task->id]) }}" method="POST" class="hidden">
                                        @csrf
                                        @method('PATCH')
                                    </form>

                                    <form id="resume-working-on-task-{{ $task->id }}-form" action="{{ route('projects.tasks.resume', ['project' => $project->id, 'task' => $task->id]) }}" method="POST" class="hidden">
                                        @csrf
                                        @method('PATCH')
                                    </form>

                                    <form id="return-working-on-task-{{ $task->id }}-form" action="{{ route('projects.tasks.return', ['project' => $project->id, 'task' => $task->id]) }}" method="POST" class="hidden">
                                        @csrf
                                        @method('PATCH')
                                    </form>
                                @empty
                                    <div class="card">
                                        <div class="card-header">There are no tasks!</div>
                                    </div>
                                @endforelse
                            </div>
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
            if($('#kanban-message').val()) {
                if($('#kanban-message-type').val() == "success") {
                    toastr.success($('#kanban-message').val());
                } else if($('#kanban-message-type').val() == "info") {
                    toastr.info($('#kanban-message').val());
                } else {
                    toastr.error($('#kanban-message').val());            
                }
            }; 

            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>
@endsection