@extends('layouts.master', ['toaster' => true, 'icheck' => true, 'summernote' => true])

@section('title', __('pages.title.task'))

@section('content')
    <div class="content-wrapper">
        <!-- Content Header -->
        <div class="p-3 mb-3" style="background-color:white;">
            <a href="{{ route('tasks.index') }}" class="btn btn-sm btn-primary text-white"><i class="fas fa-caret-left mr-1"></i>Back</a>
            <a href="{{ route('tasks.edit', $task) }}" class="btn btn-sm btn-primary text-white"><i class="fas fa-pencil-alt mr-1"></i>Edit</a>
            @include('tasks.partials.buttons', ['buttonSize' => 'sm'])
        </div>
        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <x-site.flash-messages :message="Session::get('message')" :type="Session::get('type')" />
                <div class="row">
                    <div class="col-md-5">
                        @include('tasks.partials.informations')
                        <x-activity-feed.card />
                    </div>
                    <div class="col-md-7">
                        <x-todo.card :parent="['task' => $task]" checker-form-partial="tasks.todos.forms.check" :create-form-route="route('tasks.todos.create', $task)" edit-form-route-name="tasks.todos.edit" :todos="$task->todos" />
                        <x-file.card :upload-form-route="route('tasks.files.upload', $task)" :files="$task->files" />
                        <x-comment.card :comments="$task->comments" :parent="['task' => $task]" :store-form-route="route('tasks.comments.store', $task)" update-form-route-name="tasks.comments.update" /> 
                    </div>
                </div> 
            </div>
        </section>
    </div>
@endsection