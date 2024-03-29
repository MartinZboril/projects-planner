@extends('layouts.master', ['summernote' => true, 'toaster' => true, 'icheck' => true, 'task' => true, 'todo' => true, 'comment' => true, 'file' => true])

@section('title', __('pages.title.project'))

@section('content')
    <div class="content-wrapper">
        <!-- Content Header -->
        <div class="p-3 mb-3" style="background-color:white;">
            <a href="{{ route('projects.tasks.index', $task->project) }}" class="btn btn-sm btn-primary text-white"><i class="fas fa-caret-left mr-1"></i>Back</a>
            <a href="{{ route('projects.tasks.edit', ['project' => $task->project, 'task' => $task]) }}" class="btn btn-sm btn-primary text-white"><i class="fas fa-pencil-alt mr-1"></i>Edit</a>
            @include('tasks.partials.buttons', ['buttonSize' => 'sm', 'type' => 'detail', 'tableIdentifier' => '', 'redirect' => route('projects.tasks.index', $task->project)])
        </div>
        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <x-site.flash-messages :message="Session::get('message')" :type="Session::get('type')" />
                <div class="row">
                    <div class="col-md-5">
                        @include('tasks.partials.informations', ['project' => true])
                        <x-activity-feed.card :activities="$task->activities" />
                    </div>
                    <div class="col-md-7">
                        <div id="ajax"></div>
                        <x-todo.card :todos="$task->todos" type="projects" :create-form-route="route('projects.tasks.todos.create', ['project' => $task->project, 'task' => $task])" />
                        <x-file.card :upload-form-route="route('projects.tasks.files.upload', ['project' => $task->project, 'task' => $task])" :parent="['project' => $task->project, 'task' => $task]" destroy-form-route-name="tasks.files.destroy" :files="$task->files" />
                        <x-comment.card :comments="$task->comments" :parent="['project' => $task->project, 'task' => $task]" :store-form-route="route('projects.tasks.comments.store', ['project' => $task->project, 'task' => $task])" update-form-route-name="projects.tasks.comments.update" destroy-form-route-name="projects.tasks.comments.destroy" />
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
