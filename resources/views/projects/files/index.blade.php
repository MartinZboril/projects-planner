@extends('layouts.master', ['toaster' => true, 'project' => true, 'file' => true])

@section('title', __('pages.title.project'))

@section('content')
    <div class="content-wrapper">
        <!-- Content Header -->
        <div class="p-3 mb-3" style="background-color:white;">
            @include('projects.partials.actions')
        </div>
        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="card-header p-0 pb-2 mb-2">
                    @include('projects.partials.header', ['active' => 'file'])
                </div>          
                <x-site.flash-messages :message="Session::get('message')" :type="Session::get('type')" />
                <x-file.card :upload-form-route="route('projects.files.upload', $project)" :files="$project->files" :parent="[$project]" destroy-form-route-name="projects.files.destroy" :display-header="false" />
            </div>
        </section>
    </div>
@endsection