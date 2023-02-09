@extends('layouts.master')

@section('title', __('pages.title.rate'))

@section('content')
    <div class="content-wrapper">
        <!-- Content Header -->
        <div class="p-3 mb-3" style="background-color:white;">
            <a href="{{ route('users.show', $user->id) }}" class="btn btn-sm btn-primary text-white"><i class="fas fa-caret-left mr-1"></i>Back</a>
        </div>
        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <!-- Form -->
                <form action="{{ route('users.rates.store', ['user' => $user->id]) }}" method="post">
                    @csrf
                    @method('POST')
                    @include('users.rates.forms.fields', ['rate' => $rate, 'type' => 'create'])
                    <input type="hidden" name="user_id" value="{{ $user->id }}">
                </form>     
            </div>
        </section>
    </div>
@endsection