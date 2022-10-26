@extends('layouts.master')

@section('title', __('pages.title.project'))

@section('content')
<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="p-3 rounded-0 mb-3" style="background-color:white;">
        <a href="{{ route('projects.task.detail', ['project' => $project->id, 'task' => $task->id]) }}" class="btn btn-sm btn-primary text-white"><i class="fas fa-caret-left mr-1"></i>Back</a>
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            @include('todos.forms.store', ['redirect' => 'projects'])
        </div>
    </section>
    <!-- /.content -->
  </div>
@endsection