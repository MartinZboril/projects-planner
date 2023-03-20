@extends('layouts.master', ['summernote' => true, 'toaster' => true, 'client' => true])

@section('title', __('pages.title.client'))

@section('content')
    <div class="content-wrapper">
        <!-- Content Header -->
        <div class="p-3 mb-3" style="background-color:white;">
            @include('clients.partials.actions')
        </div>
        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="card-header p-0 pb-2 mb-2">
                    @include('clients.partials.header', ['active' => 'comment'])
                </div>          
                <x-site.flash-messages :message="Session::get('message')" :type="Session::get('type')" />
                <x-comment.card :comments="$client->comments" :parent="[$client]" :store-form-route="route('clients.comments.store', $client)" update-form-route-name="clients.comments.update" :display-header="false" /> 
            </div>
        </section>
    </div>
@endsection