@extends('layouts.master')

@section('title', __('pages.title.timer'))

@push('styles')
    <!-- Toastr -->
    <link rel="stylesheet" href="{{ asset('plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">
@endpush

@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="p-3 rounded-0 mb-3" style="background-color:white;">
        <a href="{{ route('projects.timesheets', $project->id) }}" class="btn btn-sm btn-primary text-white"><i class="fas fa-caret-left mr-1"></i>Back</a>
    </div>
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <!-- Form -->
            <form action="{{ route('timers.update', $timer->id) }}" method="post">
                @csrf
                @method('PATCH')
                <div class="row">
                    <div class="col-md-7">
                        <div class="card card-primary card-outline rounded-0">
                            <div class="card-header">Edit timer</div>
                            <div class="card-body">
                                <div class="form-group required">
                                    <label for="since-datetimepicker" class="control-label">Since</label>
                                    <div class="input-group date" id="since-datetimepicker" data-target-input="nearest">
                                        <input type="text" name="since" class="form-control datetimepicker-input @error('since') is-invalid @enderror" data-target="#since-datetimepicker" value="{{ old('since', $timer->since->format('Y-m-d H:i')) }}" autocomplete="off"/>
                                        <div class="input-group-append" data-target="#since-datetimepicker" data-toggle="datetimepicker">
                                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                        </div>
                                        @error('since')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group required">
                                    <label for="until-datetimepicker" class="control-label">Until</label>
                                    <div class="input-group date" id="until-datetimepicker" data-target-input="nearest">
                                        <input type="text" name="until" class="form-control datetimepicker-input @error('until') is-invalid @enderror" data-target="#until-datetimepicker" value="{{ old('until', $timer->until->format('Y-m-d H:i')) }}" autocomplete="off"/>
                                        <div class="input-group-append" data-target="#until-datetimepicker" data-toggle="datetimepicker">
                                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                        </div>
                                        @error('until')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group required">
                                    <label for="rate-id" class="control-label">Rate</label>
                                    <select class="form-control @error('rate_id') is-invalid @enderror" name="rate_id" id="rate-id" style="width: 100%;">
                                        <option disabled selected value>Choose rate</option>
                                        @foreach(Auth::User()->rates as $rate)
                                            <option value="{{ $rate->id }}" @selected(old('rate_id', $timer->rate_id) == $rate->id)>{{ $rate->name }} ({{ $rate->value }})</option>
                                        @endforeach
                                    </select>
                                    @error('rate_id')
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
                                <input type="submit" name="save" class="btn btn-sm btn-primary mr-1" value="Save"> or <a href="{{ route('projects.timesheets', $project->id) }}" class="cancel-btn">Close</a></span>
                            </div>
                        </div>
                    </div>
                </div> 
            </form>     
        </div>
    </section>
</div>
@endsection

@push('scripts')
    <!-- DatePickers -->
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.0.1/js/tempusdominus-bootstrap-4.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.1.2/js/tempusdominus-bootstrap-4.js"></script>
    <!-- Custom -->
    <script type="text/javascript">
        $(document).ready(function(){
            $('#since-datetimepicker').datetimepicker({
                locale: 'cs',
                format: 'YYYY-MM-DD HH:mm',
                icons: {
                    time: "fas fa-clock",
                    date: "fa fa-calendar",
                    up: "fa fa-arrow-up",
                    down: "fa fa-arrow-down"
                }
            });

            $('#until-datetimepicker').datetimepicker({
                locale: 'cs',
                format: 'YYYY-MM-DD HH:mm',
                useCurrent: false,
                icons: {
                    time: "fas fa-clock",
                    date: "fa fa-calendar",
                    up: "fa fa-arrow-up",
                    down: "fa fa-arrow-down"
                }
            });

            $("#since-datetimepicker").on("change.datetimepicker", function (e) {
                $('#until-datetimepicker').datetimepicker('minDate', e.date);
            });

            $("#until-datetimepicker").on("change.datetimepicker", function (e) {
                $('#since-datetimepicker').datetimepicker('maxDate', e.date);
            });

            $('#rate-id').select2({
                theme: 'bootstrap4',
                placeholder: 'select rate'
            });
        });
    </script>
@endpush