@extends('layouts.master')

@section('title', __('pages.title.task'))

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
        @else
            <a href="#" class="btn btn-sm btn-danger" onclick="event.preventDefault(); document.getElementById('return-working-on-task-form').submit();"><i class="fas fa-undo mr-1"></i> Return</a>
        @endif
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <input type="hidden" id="message-content" value="{{ Session::get('message') }}">
            <input type="hidden" id="message-type" value="{{ Session::get('type') }}">

            @include('tasks.partials.information', ['task' => $task, 'project' => null])                    
        </div>
    </section>
<!-- /.content -->
</div>

<!-- task status change forms -->
@include('tasks.forms.change', ['id' => 'start-working-on-task-form', 'task' => $task, 'statusId' => 2, 'redirect' => 'tasks'])    
@include('tasks.forms.change', ['id' => 'complete-working-on-task-form', 'task' => $task, 'statusId' => 3, 'redirect' => 'tasks'])    
@include('tasks.forms.change', ['id' => 'return-working-on-task-form', 'task' => $task, 'statusId' => 1, 'redirect' => 'tasks'])    
<!-- pause work on task form -->
@include('tasks.forms.pause', ['id' => 'stop-working-on-task-form', 'task' => $task, 'action' => 1, 'redirect' => 'tasks'])    
@include('tasks.forms.pause', ['id' => 'resume-working-on-task-form', 'task' => $task, 'action' => 0, 'redirect' => 'tasks'])    

@endsection

@push('scripts')
    <!-- Toastr -->
    <script src="{{ asset('plugins/toastr/toastr.min.js' ) }}"></script>
    <script src="{{ asset('js/toastr.js') }}"></script>
@endpush