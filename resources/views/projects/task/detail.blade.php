@extends('layouts.master')

@section('title', __('pages.title.project'))

@push('styles')
    <!-- Toastr -->
    <link rel="stylesheet" href="{{ asset('plugins/toastr/toastr.css') }}">
@endpush

@section('content')
<div class="content-wrapper">
    <!-- Content Header -->
    <div class="p-3 mb-3" style="background-color:white;">
        <a href="{{ route('projects.tasks', $project->id) }}" class="btn btn-sm btn-primary text-white"><i class="fas fa-caret-left mr-1"></i>Back</a>
        <a href="{{ route('projects.task.edit', ['project' => $project->id, 'task' => $task->id]) }}" class="btn btn-sm btn-primary text-white"><i class="fas fa-pencil-alt mr-1"></i>Edit</a>
        @include('tasks.partials.detail.action', ['task' => $task])
    </div>
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <!-- Message -->
            @include('site.partials.message', ['message' => Session::get('message'), 'type' => Session::get('type')])
            <!-- Content -->
            @include('tasks.partials.detail.information', ['task' => $task, 'project' => $project])            
        </div>
    </section>
</div>

<!-- Task status change forms -->
@include('tasks.forms.change', ['id' => 'start-working-on-task-form', 'task' => $task, 'statusId' => 2, 'redirect' => 'projects'])    
@include('tasks.forms.change', ['id' => 'complete-working-on-task-form', 'task' => $task, 'statusId' => 3, 'redirect' => 'projects'])    
@include('tasks.forms.change', ['id' => 'return-working-on-task-form', 'task' => $task, 'statusId' => 1, 'redirect' => 'projects'])    
<!-- Pause work on task form -->
@include('tasks.forms.pause', ['id' => 'stop-working-on-task-form', 'task' => $task, 'action' => 1, 'redirect' => 'projects'])    
@include('tasks.forms.pause', ['id' => 'resume-working-on-task-form', 'task' => $task, 'action' => 0, 'redirect' => 'projects'])    

@endsection

@push('scripts')
    <!-- Toastr -->
    <script src="{{ asset('plugins/toastr/toastr.min.js' ) }}"></script>
    <script src="{{ asset('js/toastr.js') }}"></script>
@endpush