@extends('layouts.master', ['summernote' => true, 'toaster' => true, 'icheck' => true])

@section('title', __('pages.title.project'))

@section('content')
    <div class="content-wrapper">
        <!-- Content Header -->
        <div class="p-3 mb-3" style="background-color:white;">
            <a href="{{ route('projects.tasks.index', $task->project) }}" class="btn btn-sm btn-primary text-white"><i class="fas fa-caret-left mr-1"></i>Back</a>
            <a href="{{ route('projects.tasks.edit', ['project' => $task->project, 'task' => $task]) }}" class="btn btn-sm btn-primary text-white"><i class="fas fa-pencil-alt mr-1"></i>Edit</a>
            @include('projects.tasks.partials.buttons', ['project' => $task->project, 'task' => $task, 'buttonSize' => 'sm', 'buttonText' => true])
        </div>
        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <!-- Message -->
                <x-site.flash-messages :message="Session::get('message')" :type="Session::get('type')" />
                <!-- Content -->
                <div class="row">
                    <div class="col-md-5">
                        <x-task.ui.informations :$task />
                        <x-activity-feed.card />
                    </div>
                    <div class="col-md-7">
                        <x-todo.card :parent="['project' => $task->project]" checker-form-partial="projects.tasks.todos.forms.check" :create-form-route="route('projects.tasks.todos.create', ['project' => $task->project, 'task' => $task])" edit-form-route-name="projects.tasks.todos.edit" :todos="$task->todos" />
                        <x-file.card :upload-form-route="route('projects.tasks.files.upload', ['project' => $task->project, 'task' => $task])" :files="$task->files" :display-header="true" />
                        <x-comment.card :comments="$task->comments" :parent="['project' => $task->project, 'task' => $task]" :store-form-route="route('projects.tasks.comments.store', ['project' => $task->project, 'task' => $task])" update-form-route-name="projects.tasks.comments.update" :display-header="true" />     
                    </div>
                </div> 
            </div>
        </section>
    </div>
@endsection