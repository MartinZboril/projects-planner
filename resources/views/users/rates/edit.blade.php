@extends('layouts.master')

@section('title', __('pages.title.rate'))

@section('content')
    <div class="content-wrapper">
        <!-- Content Header -->
        <div class="p-3 mb-3" style="background-color:white;">
            <a href="{{ route('users.show', $user) }}" class="btn btn-sm btn-primary text-white"><i class="fas fa-caret-left mr-1"></i>Back</a>
        </div>
        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <!-- Form -->
                <form action="{{ route('users.rates.update', ['user' => $user, 'rate' => $rate]) }}" method="post">
                    @csrf
                    @method('PATCH')
                    <x-rate.fields :$rate :$user type="edit" />
                </form>     
            </div>
        </section>
    </div>
@endsection