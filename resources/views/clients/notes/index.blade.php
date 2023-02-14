@extends('layouts.master', ['datatables' => true, 'toaster' => true])

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
                    <x-client.ui.header :$client active="note" />
                </div>          
                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <a href="{{ route('clients.notes.create', $client) }}" class="btn-sm btn-primary"><i class="fas fa-plus mr-1"></i>Create</a>
                    </div>
                    <div class="card-body">
                        <!-- Message -->
                        <x-site.flash-messages :message="Session::get('message')" :type="Session::get('type')" />
                        <!-- Content -->
                        <x-note.card :notes="$client->notes" edit-form-route-name="clients.notes.edit" :parent="$client" />
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection