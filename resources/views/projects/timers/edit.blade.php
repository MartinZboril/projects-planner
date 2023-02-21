@extends('layouts.master', ['momment' => true, 'tempusdominus' => true, 'select2' => true])

@section('title', __('pages.title.timer'))

@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="p-3 mb-3" style="background-color:white;">
            <a href="{{ route('projects.timers.index', $timer->project) }}" class="btn btn-sm btn-primary text-white"><i class="fas fa-caret-left mr-1"></i>Back</a>
        </div>
        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <!-- Form -->
                <form action="{{ route('projects.timers.update', ['project' => $timer->project, 'timer' => $timer]) }}" method="post">
                    @csrf
                    @method('PATCH')
                    <x-timer.fields :$timer :project="$timer->project" type="edit" /> 
                </form>     
            </div>
        </section>
    </div>
@endsection