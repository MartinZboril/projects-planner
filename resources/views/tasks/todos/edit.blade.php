@extends('layouts.master')

@section('title', __('pages.title.todo'))

@section('content')
    <div class="content-wrapper">
        <!-- Content Header -->
        <div class="p-3 mb-3" style="background-color:white;">
            <a href="{{ route('tasks.show', $todo->task) }}" class="btn btn-sm btn-primary text-white"><i class="fas fa-caret-left mr-1"></i>Back</a>
            <a href="#" onclick="if (!confirm('Do you really want to remove todo?')) return false; event.preventDefault(); document.getElementById('destroy-todo-form').submit();" class="btn btn-sm btn-danger"><i class="fas fa-trash-alt"></i></a>
            <form id="destroy-todo-form" method="post" action="{{route('tasks.todos.destroy', ['task' => $todo->task, 'todo' => $todo])}}">
                @method('delete')
                @csrf
                <input type="hidden" name="redirect" value="1">
            </form>
        </div>
        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <form action="{{ route('tasks.todos.update', ['todo' => $todo, 'task' => $todo->task]) }}" method="post">
                    @csrf
                    @method('PATCH')
                    <x-todo.fields type="edit" :task="$todo->task" :$todo />
                </form>   
            </div>
        </section>
    </div>
@endsection