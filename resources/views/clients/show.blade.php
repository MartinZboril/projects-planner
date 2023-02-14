@extends('layouts.master', ['toaster' => true])

@section('title', __('pages.title.client'))

@section('content')
    <div class="content-wrapper">
        <!-- Content Header -->
        <div class="p-3 mb-3" style="background-color:white;">
            <x-client.ui.actions :$client />    
        </div>
        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="card-header p-0 pb-2 mb-2">
                    <x-client.ui.header :$client active="client" />
                </div>
                <!-- Message -->
                <x-site.flash-messages :message="Session::get('message')" :type="Session::get('type')" />
                <!-- Content -->                
                <div class="row">
                    <div class="col-md-5">
                        <x-client.ui.informations :$client />
                    </div>
                    <div class="col-md-7">
                        <x-activity-feed.card />
                    </div>
                </div>  
            </div>
        </section>
    </div>
@endsection