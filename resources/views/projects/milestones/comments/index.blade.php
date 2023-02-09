@extends('layouts.master', ['summernote' => true, 'toaster' => true])

@section('title', __('pages.title.client'))

@section('content')
    <div class="content-wrapper">
        <!-- Content Header -->
        <div class="p-3 mb-3" style="background-color:white;">
            @include('clients.partials.action', ['client' => $client])
        </div>
        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="card-header p-0 pb-2 mb-2">
                    @include('clients.partials.header', ['active' => 'comment'])
                </div>          
                <div class="card card-primary card-outline">
                    <div class="card-body">
                        <!-- Message -->
                        @include('site.partials.message', ['message' => Session::get('message'), 'type' => Session::get('type')])
                        <!-- Content -->
                        @include('site.comments.list', ['comments' => $client->comments, 'comment' => $comment, 'createFormPartial' => 'clients.comments.create', 'editFormPartial' => 'clients.comments.edit'])
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection