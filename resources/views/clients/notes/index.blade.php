@extends('layouts.master', ['datatables' => true, 'toaster' => true, 'client' => true, 'note' => true])

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
                    @include('clients.partials.header', ['active' => 'note'])
                </div>          
                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <a href="{{ route('clients.notes.create', $client) }}" class="btn-sm btn-primary"><i class="fas fa-plus mr-1"></i>Create</a>
                    </div>
                    <div class="card-body">
                        <x-site.flash-messages :message="Session::get('message')" :type="Session::get('type')" />
                        <x-note.card :notes="$client->notes" edit-form-route-name="clients.notes.edit" :parent="['client' => $client]" type="client" />
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection