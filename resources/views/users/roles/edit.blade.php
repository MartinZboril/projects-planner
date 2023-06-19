@extends('layouts.master')

@section('title', __('pages.title.role'))

@section('content')
    <div class="content-wrapper">
        <!-- Content Header -->
        <div class="p-3 mb-3" style="background-color:white;">
            <a href="{{ route('users.roles.index') }}" class="btn btn-sm btn-primary text-white"><i class="fas fa-caret-left mr-1"></i>Back</a>
        </div>
        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <!-- Form -->
                <form action="{{ route('users.roles.update', $role) }}" method="post">
                    @csrf
                    @method('PATCH')
                    <x-role.fields :$role type="edit" />
                </form>     
            </div>
        </section>
    </div>
@endsection