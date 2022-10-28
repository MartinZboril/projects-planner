@extends('layouts.master')

@section('title', __('pages.title.rate'))

@section('styles')
    <link rel="stylesheet" href="{{ asset('plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">
@endsection

@section('content')
<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="p-3 rounded-0 mb-3" style="background-color:white;">
        <a href="{{ route('users.detail', $user->id) }}" class="btn btn-sm btn-primary text-white"><i class="fas fa-caret-left mr-1"></i>Back</a>
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <form action="{{ route('rates.update', $rate->id) }}" method="post">
                @csrf
                @method('PATCH')
                <div class="row">
                    <div class="col-md-7">
                        <div class="card card-primary card-outline rounded-0">
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
                                        <input type="checkbox" name="is_active" class="custom-control-input @error('is_active') is-invalid @enderror" id="is-active" value="1" {{ (old('is_active', $rate->is_active)) ? 'checked' : '' }}>
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
                        <div class="card card-primary card-outline rounded-0">
                            <div class="card-header">Settings</div>
                            <div class="card-body">
                            </div>
                        </div>
                        <div class="card rounded-0">
                            <div class="card-body">
                                <input type="submit" name="save" class="btn btn-sm btn-primary mr-1" value="Save"> or <a href="{{ route('users.detail', $user->id) }}" class="cancel-btn">Close</a></span>
                            </div>
                        </div>
                    </div>
                </div>    
            </form>     
        </div>
    </section>
    <!-- /.content -->
  </div>
@endsection