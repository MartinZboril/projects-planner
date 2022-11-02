@extends('layouts.master')

@section('title', __('pages.title.project'))

@push('styles')
    <!-- Toastr -->
    <link rel="stylesheet" href="{{ asset('plugins/toastr/toastr.css') }}">
@endpush

@section('content')
<div class="content-wrapper">
    <!-- Content Header -->
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
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="card-header p-0 pb-2">
                @include('projects.partials.header', ['active' => 'kanban'])
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="card card-info">
                            <div class="card-header">New</div>
                            <div class="card-body">
                                <!-- Message -->
                                @include('site.partials.message', ['message' => Session::get('message'), 'type' => Session::get('type')])
                                <!-- Content -->
                                @forelse ($project->newTasks as $task)
                                    <div class="card card-info card-outline">
                                        <div class="card-header">
                                            <div class="card-title">
                                                <a href="{{ route('projects.task.detail', ['project' => $project->id, 'task' => $task->id]) }}">{{ $task->name }}</a>
                                            </div>
                                            <div class="card-tools">
                                                <a href="#" class="btn btn-sm btn-tool" onclick="event.preventDefault(); document.getElementById('start-working-on-task-{{ $task->id }}-form').submit();"><i class="fas fa-play" data-toggle="tooltip" data-placement="bottom" title="Start"></i></a>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            @include('projects.task.information')
                                        </div>
                                    </div>
                                    @include('tasks.forms.change', ['id' => 'start-working-on-task-' . $task->id . '-form', 'task' => $task, 'statusId' => 2, 'redirect' => 'kanban'])    
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
                                            <div class="card-title">
                                                <a href="{{ route('projects.task.detail', ['project' => $project->id, 'task' => $task->id]) }}">{{ $task->name }}</a>
                                                @if($task->is_stopped)
                                                    <span class="badge badge-@include('tasks.partials.colour', ['task' => $task]) ml-2" style='font-size:14px;'>@include('tasks.partials.status', ['task' => $task])</span>
                                                @elseif($task->is_returned)
                                                    <span class="badge badge-@include('tasks.partials.colour', ['task' => $task]) ml-2" style='font-size:14px;'>@include('tasks.partials.status', ['task' => $task])</span>
                                                @endif
                                            </div>
                                            <div class="card-tools">
                                                @if ($task->is_stopped)
                                                    <a href="#" class="btn btn-sm btn-tool" onclick="event.preventDefault(); document.getElementById('resume-working-on-task-{{ $task->id }}-form').submit();"><i class="fas fa-hourglass-start" data-toggle="tooltip" data-placement="bottom" title="Resume"></i></a>
                                                @else
                                                    <a href="#" class="btn btn-sm btn-tool" onclick="event.preventDefault(); document.getElementById('complete-working-on-task-{{ $task->id }}-form').submit();"><i class="fas fa-check" data-toggle="tooltip" data-placement="bottom" title="Complete"></i></a>
                                                    <a href="#" class="btn btn-sm btn-tool" onclick="event.preventDefault(); document.getElementById('stop-working-on-task-{{ $task->id }}-form').submit();"><i class="fas fa-stop" data-toggle="tooltip" data-placement="bottom" title="Stop"></i></a>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            @include('projects.task.information')
                                        </div>
                                    </div>
                                    @include('tasks.forms.change', ['id' => 'complete-working-on-task-' . $task->id . '-form', 'task' => $task, 'statusId' => 3, 'redirect' => 'kanban'])    
                                    @include('tasks.forms.pause', ['id' => 'stop-working-on-task-' . $task->id . '-form', 'task' => $task, 'action' => 1, 'redirect' => 'kanban'])    
                                    @include('tasks.forms.pause', ['id' => 'resume-working-on-task-' . $task->id . '-form', 'task' => $task, 'action' => 0, 'redirect' => 'kanban'])    
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
                                            <div class="card-title">
                                                <a href="{{ route('projects.task.detail', ['project' => $project->id, 'task' => $task->id]) }}">{{ $task->name }}</a>
                                            </div>
                                            <div class="card-tools">
                                                <a href="#" class="btn btn-sm btn-tool" onclick="event.preventDefault(); document.getElementById('return-working-on-task-{{ $task->id }}-form').submit();"><i class="fas fa-undo" data-toggle="tooltip" data-placement="bottom" title="Return"></i></a>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            @include('projects.task.information')
                                        </div>
                                    </div>
                                    @include('tasks.forms.change', ['id' => 'return-working-on-task-' . $task->id . '-form', 'task' => $task, 'statusId' => 1, 'redirect' => 'kanban'])    
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
</div>

@include('projects.partials.timers')

@endsection

@push('scripts')
    <!-- Toastr -->
    <script src="{{ asset('plugins/toastr/toastr.min.js' ) }}"></script>
    <script src="{{ asset('js/toastr.js') }}"></script>
    <!-- Custom -->
    <script>
        $(function () {
            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>
@endpush