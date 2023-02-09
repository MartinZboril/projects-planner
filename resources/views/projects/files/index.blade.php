@extends('layouts.master', ['toaster' => true])

@section('title', __('pages.title.project'))

@section('content')
    <div class="content-wrapper">
        <!-- Content Header -->
        <div class="p-3 mb-3" style="background-color:white;">
            @include('projects.partials.action', ['project' => $project])
        </div>
        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="card-header p-0 pb-2 mb-2">
                    @include('projects.partials.header', ['active' => 'file'])
                </div>          
                <div class="card card-primary card-outline">
                    <div class="card-body">
                        <!-- Message -->
                        @include('site.partials.message', ['message' => Session::get('message'), 'type' => Session::get('type')])
                        <!-- Content -->
                        @include('projects.files.upload', ['project' => $project])
                        <hr>
                        @include('site.files.list', ['files' => $project->files])
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection