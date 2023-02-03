@extends('layouts.master', ['toaster' => true, 'icheck' => true, 'summernote' => true])

@section('title', __('pages.title.task'))

@section('content')
    <div class="content-wrapper">
        <!-- Content Header -->
        <div class="p-3 mb-3" style="background-color:white;">
            <a href="{{ route('tasks.index') }}" class="btn btn-sm btn-primary text-white"><i class="fas fa-caret-left mr-1"></i>Back</a>
            <a href="{{ route('tasks.edit', $task->id) }}" class="btn btn-sm btn-primary text-white"><i class="fas fa-pencil-alt mr-1"></i>Edit</a>
            @include('tasks.partials.buttons', ['task' => $task, 'buttonSize' => 'sm', 'buttonText' => true])
        </div>
        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <!-- Message -->
                @include('site.partials.message', ['message' => Session::get('message'), 'type' => Session::get('type')])
                <!-- Content -->
                @include('tasks.partials.detail.information', ['task' => $task, 'project' => null])                    
            </div>
        </section>
    </div>
@endsection