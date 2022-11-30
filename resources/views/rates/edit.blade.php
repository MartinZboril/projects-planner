@extends('layouts.master')

@section('title', __('pages.title.rate'))

@section('content')
    <div class="content-wrapper">
        <!-- Content Header -->
        <div class="p-3 mb-3" style="background-color:white;">
            <a href="{{ route('users.detail', $user->id) }}" class="btn btn-sm btn-primary text-white"><i class="fas fa-caret-left mr-1"></i>Back</a>
        </div>
        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <!-- Form -->
                <form action="{{ route('rates.update', $rate->id) }}" method="post">
                    @csrf
                    @method('PATCH')
                    <div class="row">
                        <div class="col-md-7">
                            <div class="card card-primary card-outline">
                                <div class="card-header">Edit rate</div>
                                <div class="card-body">
                                    <div class="form-group required">
                                        <label for="name" class="control-label">Name</label>
                                        <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" placeholder="name" value="{{ old('name', $rate->name) }}" autocomplete="off">
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <div class="custom-control custom-switch">
                                            <input type="checkbox" name="is_active" class="custom-control-input @error('is_active') is-invalid @enderror" id="is-active" value="1" @checked(old('is_active', $rate->is_active))>
                                            <label class="custom-control-label" for="is-active">Active</label>
                                        </div>
                                        @error('is_active')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>  
                                    <div class="form-group required">
                                        <label for="value" class="control-label">Value</label>
                                        <input type="number" name="value" id="value" class="form-control @error('value') is-invalid @enderror" placeholder="value" value="{{ old('value', $rate->value) }}" autocomplete="off">
                                        @error('value')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="card card-primary card-outline">
                                <div class="card-header">Settings</div>
                                <div class="card-body">
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <input type="submit" name="save" class="btn btn-sm btn-primary mr-1" value="Save"> or <a href="{{ route('users.detail', $user->id) }}" class="cancel-btn">Close</a></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>     
            </div>
        </section>
    </div>
@endsection