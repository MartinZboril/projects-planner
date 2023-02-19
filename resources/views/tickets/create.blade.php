@extends('layouts.master', ['select2' => true, 'summernote' => true])

@section('title', __('pages.title.ticket'))

@section('content')
    <div class="content-wrapper">
        <!-- Content Header -->
        <div class="p-3 mb-3" style="background-color:white;">
            <a href="{{ route('tickets.index') }}" class="btn btn-sm btn-primary text-white"><i class="fas fa-caret-left mr-1"></i>Back</a>
        </div>
        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <!-- Form -->
                <form action="{{ route('tickets.store') }}" method="post">
                    @csrf
                    @method('POST')
                    <x-ticket.fields type="create" />
                </form>         
            </div>
        </section>
    </div>
@endsection