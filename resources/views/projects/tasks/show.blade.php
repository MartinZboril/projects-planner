@extends('layouts.master', ['summernote' => true, 'toaster' => true, 'icheck' => true])

@section('title', __('pages.title.project'))

@section('content')
    <div class="content-wrapper">
        <!-- Content Header -->
        <div class="p-3 mb-3" style="background-color:white;">
            <a href="{{ route('projects.tasks.index', $project->id) }}" class="btn btn-sm btn-primary text-white"><i class="fas fa-caret-left mr-1"></i>Back</a>
            <a href="{{ route('projects.tasks.edit', ['project' => $project, 'task' => $task]) }}" class="btn btn-sm btn-primary text-white"><i class="fas fa-pencil-alt mr-1"></i>Edit</a>
            @include('projects.tasks.partials.buttons', ['project' => $project, 'task' => $task, 'buttonSize' => 'sm', 'buttonText' => true])
        </div>
        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <!-- Message -->
                <x-site.flash-messages :message="Session::get('message')" :type="Session::get('type')" />
                <!-- Content -->
                <div class="row">
                    <div class="col-md-5">
                        <div class="card card-primary card-outline">
                            <div class="card-header">
                                {{ $task->name }}
                                <span class="badge badge-@include('projects.tasks.partials.colour', ['project' => $project, 'task' => $task]) ml-2" style='font-size:14px;'>@include('projects.tasks.partials.status', ['project' => $project, 'task' => $task])</span>
                                @if($task->overdue)<span class="badge badge-danger ml-1" style="font-size:14px;">Overdue</span>@endif
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12 col-sm-4">
                                        <div class="info-box bg-light">
                                            <div class="info-box-content">
                                                <span class="info-box-text text-center text-muted">Due date</span>
                                                <span class="info-box-number text-center text-muted mb-0"><span class="badge badge-{{ $task->overdue ? 'danger' : 'secondary' }}">{{ $task->due_date->format('d.m.Y') }}</span></span>
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
                                <span class="d-block">Project: <b><a href="{{ route('projects.show', $task->project->id) }}">{{ $task->project->name }}</a></b></span>
                                <span class="d-block">Client: <b><a href="{{ route('clients.show', $task->project->client->id) }}">{{ $task->project->client->name }}</a></b></span>
                                <span class="d-block">Milestone: <b>@if($task->milestone)<a href="{{ route('projects.milestones.show', ['project' => $task->milestone->project->id, 'milestone' => $task->milestone->id]) }}">{{ $task->milestone_label }}</a>@else{{ $task->milestone_label }}@endif</b></span>
                                <span class="d-block">User: <b><a href="{{ route('users.show', $task->user->id) }}">{{ $task->user->full_name }}</a></b></span>
                                <span class="d-block">Author: <b><a href="{{ route('users.show', $task->author->id) }}">{{ $task->author->full_name }}</a></b></span>
                                <span class="d-block">Status: <b>@include('projects.tasks.partials.status', ['task' => $task])</b></span>
                                <hr>
                                {!! $task->description !!}
                            </div>
                        </div>
                        <div class="card card-primary card-outline">
                            <div class="card-header">Activity Feed</div>
                            <div class="card-body">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-7">
                        <div class="card card-primary card-outline">
                            <div class="card-header">
                                <div class="card-title">
                                    <i class="fas fa-clipboard-list mr-1"></i>
                                    ToDo List
                                </div>
                                <div class="card-tools">
                                    <a href="{{ route('projects.tasks.todos.create', ['project' => $project, 'task' => $task]) }}" class="btn btn-sm btn-primary btn-sm float-right"><i class="fas fa-plus"></i>Add</a>
                                </div>
                            </div>
                            <div class="card-body">
                                @include('projects.tasks.todos.partials.list', ['todos' => $task->todos, 'project' => $project])            
                            </div>
                        </div>
                        <div class="card card-primary card-outline">
                            <div class="card-header">Files</div>
                            <div class="card-body">
                                @include('projects.tasks.files.upload', ['project' => $project, 'task' => $task])
                                <hr>
                                @include('site.files.list', ['files' => $task->files])
                            </div>
                        </div>
                        <div class="card card-primary card-outline">
                            <div class="card-header">Comments</div>
                            <div class="card-body">
                                @include('site.comments.list', ['comments' => $task->comments, 'comment' => null, 'createFormPartial' => 'projects.tasks.comments.create', 'editFormPartial' => 'projects.tasks.comments.edit'])
                            </div>
                        </div>
                    </div>
                </div> 
            </div>
        </section>
    </div>
@endsection