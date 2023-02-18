@extends('layouts.master', ['toaster' => true, 'progressbar' => true])

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
                    <x-project.ui.header :$project active="project" />
                </div>
                <!-- Message -->
                <x-site.flash-messages :message="Session::get('message')" :type="Session::get('type')" />
                <!-- Content -->                
                <div class="row">
                    <div class="col-md-4">
                        <x-project.ui.informations :$project />
                    </div>
                    <div class="col-md-8">
                        <x-project.ui.charts :$project />
                        <x-activity-feed.card />
                    </div>
                </div>  
            </div>
        </section>
    </div>
@endsection