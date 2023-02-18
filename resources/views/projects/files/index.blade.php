@extends('layouts.master', ['toaster' => true])

@section('title', __('pages.title.project'))

@section('content')
    <div class="content-wrapper">
        <!-- Content Header -->
        <div class="p-3 mb-3" style="background-color:white;">
            <x-project.ui.actions :$project />
        </div>
        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="card-header p-0 pb-2 mb-2">
                    <x-project.ui.header :$project active="file" />
                </div>          
                <!-- Message -->
                <x-site.flash-messages :message="Session::get('message')" :type="Session::get('type')" />
                <!-- Content -->
                <x-file.card :upload-form-route="route('projects.files.upload', $project)" :files="$project->files" :display-header="false" />
            </div>
        </section>
    </div>
@endsection