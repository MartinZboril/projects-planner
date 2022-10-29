@extends('layouts.master')

@section('title', __('pages.title.todo'))

@section('content')
<div class="content-wrapper">
    <!-- Content Header -->
    <div class="p-3 rounded-0 mb-3" style="background-color:white;">
        <a href="{{ route('tasks.detail', $task->id) }}" class="btn btn-sm btn-primary text-white"><i class="fas fa-caret-left mr-1"></i>Back</a>
    </div>
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            @include('todos.forms.update', ['redirect' => 'tasks'])    
        </div>
    </section>
</div>
@endsection