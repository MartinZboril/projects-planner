@extends('layouts.master', ['toaster' => true, 'client' => true, 'file' => true])

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
                    @include('clients.partials.header', ['active' => 'file'])
                </div>          
                <x-site.flash-messages :message="Session::get('message')" :type="Session::get('type')" />
                <x-file.card :upload-form-route="route('clients.files.upload', $client)" :files="$client->files" :parent="[$client]" destroy-form-route-name="clients.files.destroy" :display-header="false" />
            </div>
        </section>
    </div>
@endsection