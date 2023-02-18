@extends('layouts.master', ['momment' => true, 'tempusdominus' => true, 'select2' => true])

@section('title', __('pages.title.timer'))

@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="p-3 mb-3" style="background-color:white;">
            <a href="{{ route('projects.timers.index', $project) }}" class="btn btn-sm btn-primary text-white"><i class="fas fa-caret-left mr-1"></i>Back</a>
        </div>
        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <!-- Form -->
                <form action="{{ route('projects.timers.store', $project) }}" method="post">
                    @csrf
                    @method('POST')
                    <x-timer.fields :timer="null" :close-route="route('projects.timers.index', $project)" type="create" />              
                    <input type="hidden" name="project_id" value="{{ $project->id }}">
                </form>     
            </div>
        </section>
    </div>
@endsection