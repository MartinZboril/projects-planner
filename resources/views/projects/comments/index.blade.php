@extends('layouts.master', ['summernote' => true, 'toaster' => true])

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
                    @include('projects.partials.header', ['active' => 'comment'])
                </div>          
                <!-- Message -->
                <x-site.flash-messages :message="Session::get('message')" :type="Session::get('type')" />
                <!-- Content -->
                <x-comment.card :comments="$project->comments" :parent="[$project]" :store-form-route="route('projects.comments.store', $project)" update-form-route-name="projects.comments.update" :display-header="false" /> 
            </div>
        </section>
    </div>
@endsection