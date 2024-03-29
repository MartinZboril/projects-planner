@extends('layouts.master', ['toaster' => true, 'icheck' => true, 'summernote' => true, 'task' => true, 'todo' => true, 'comment' => true, 'file' => true])

@section('title', __('pages.title.task'))

@section('content')
    <div class="content-wrapper">
        <!-- Content Header -->
        <div class="p-3 mb-3" style="background-color:white;">
            <a href="{{ route('tasks.index') }}" class="btn btn-sm btn-primary text-white"><i class="fas fa-caret-left mr-1"></i>Back</a>
            <a href="{{ route('tasks.edit', $task) }}" class="btn btn-sm btn-primary text-white"><i class="fas fa-pencil-alt mr-1"></i>Edit</a>
            @include('tasks.partials.buttons', ['buttonSize' => 'sm', 'type' => 'detail', 'tableIdentifier' => '', 'redirect' => route('tasks.index')])
        </div>
        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <x-site.flash-messages :message="Session::get('message')" :type="Session::get('type')" />
                <div class="row">
                    <div class="col-md-5">
                        @include('tasks.partials.informations')
                        <x-activity-feed.card :activities="$task->activities" />
                    </div>
                    <div class="col-md-7">
                        <x-todo.card :todos="$task->todos" :create-form-route="route('tasks.todos.create', $task)" />
                        <x-file.card :upload-form-route="route('tasks.files.upload', $task)" :parent="[$task]" destroy-form-route-name="tasks.files.destroy" :files="$task->files" />
                        <x-comment.card :comments="$task->comments" :parent="['task' => $task]" :store-form-route="route('tasks.comments.store', $task)" update-form-route-name="tasks.comments.update" destroy-form-route-name="tasks.comments.destroy" />
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
