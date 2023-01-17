@extends('layouts.master', ['datatables' => true, 'toaster' => true])

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
                    <div class="card-header">
                        <form action="{{ route('comments.store') }}" method="post">
                            @csrf
                            @method('POST')
                            <input type="text" name="content" value="">  
                            <input type="hidden" name="parent_id" value="{{ $client->id }}">
                            <input type="hidden" name="type" value="client">
                            <input type="submit" name="save" class="btn btn-sm btn-primary mr-1" value="Save"></span>                
                        </form>     
                    </div>
                    <div class="card-body">
                        <!-- Message -->
                        @include('site.partials.message', ['message' => Session::get('message'), 'type' => Session::get('type')])
                        <!-- Content -->
                        <div class="row">
                            @forelse ($client->comments as $comment)
                                <div class="col-md-3">
                                    @include('comments.partials.card', ['comment' => $comment])
                                </div>
                            @empty
                                No comments were found!
                            @endforelse                           
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection