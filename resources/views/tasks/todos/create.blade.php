@extends('layouts.master')

@section('title', __('pages.title.todo'))

@section('content')
    <div class="content-wrapper">
        <!-- Content Header -->
        <div class="p-3 mb-3" style="background-color:white;">
            <a href="{{ route('tasks.show', $task) }}" class="btn btn-sm btn-primary text-white"><i class="fas fa-caret-left mr-1"></i>Back</a>
        </div>
        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <!-- Form -->
                <form action="{{ route('tasks.todos.store', $task) }}" method="post">
                    @csrf
                    @method('POST')
                    <x-todo.fields type="create" :$task /> 
                </form>
            </div>
        </section>
    </div>
@endsection