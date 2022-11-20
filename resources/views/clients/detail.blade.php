@extends('layouts.master')

@section('title', __('pages.title.client'))

@section('content')
<div class="content-wrapper">
    <!-- Content Header -->
    <div class="p-3 mb-3" style="background-color:white;">
        <a href="{{ route('clients.index') }}" class="btn btn-sm btn-primary text-white"><i class="fas fa-caret-left mr-1"></i>Back</a>
        <a href="{{ route('clients.edit', $client->id) }}" class="btn btn-sm btn-primary text-white"><i class="fas fa-pencil-alt mr-1"></i>Edit</a>
    </div>
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <!-- Message -->
            @include('site.partials.message', ['message' => Session::get('message'), 'type' => Session::get('type')])
            <!-- Content -->
            <div class="row">
                <div class="col-md-5">
                    <div class="card card-primary card-outline">
                        <div class="card-header">Profile</div>
                        <div class="card-body">
                            <div class="row mb-3">
                                <img src="{{ asset('dist/img/user.png') }}" class="img-circle mr-3" alt="Client Image" style="width: 75px;height: 75px;">
                                <div class="">
                                    <h5>{{ $client->name }}</h5>
                                    <i class="fas fa-envelope mr-2"></i><a href="mailto:{{ $client->email_label }}">{{ $client->email_label }}</a><br>
                                    <i class="fas fa-user-circle mr-2"></i>{{ $client->contact_person_label }} (@if($client->contact_email)<a href="mailto:{{ $client->contact_email }}">{{ $client->contact_email_label }}</a>@else{{ $client->contact_email_label }}@endif)<br>
                                </div>                            
                            </div>
                            @if ($client->note)
                                <hr>
                                {{ $client->note }}
                                <hr>                                
                            @endif
                            <ul class="list-group">
                                <li class="list-group-item active"><i class="fas fa-address-card mr-2"></i>Contacts</li>
                                <li class="list-group-item"><i class="fas fa-phone mr-2"></i>Phone: @if($client->phone)<a href="tel:{{ $client->phone }}">{{ $client->phone_label }}</a>@else{{ $client->phone_label }}@endif</li>
                                <li class="list-group-item"><i class="fas fa-mobile-alt mr-2"></i>Mobile: @if($client->mobile)<a href="tel:{{ $client->mobile }}">{{ $client->mobile_label }}</a>@else{{ $client->mobile_label }}@endif</li>
                                <li class="list-group-item"><i class="fas fa-pager mr-2"></i></i>Website: @if ($client->website)<a href="{{ $client->website }}">{{ $client->website }}</a>@else{{ 'NaN' }}@endif</li>
                                <li class="list-group-item"><i class="fas fa-envelope mr-2"></i>Email: <a href="mailto:{{ $client->email_label }}">{{ $client->email_label }}</a></li>
                                <li class="list-group-item"><i class="fas fa-map mr-2"></i>Address: {{ $client->street_label }}; {{ $client->city_label }}; {{ $client->zip_code_label }}; {{ $client->country_label }}
                            </ul>
                            @if ($client->facebook || $client->twitter || $client->instagram || $client->linekedin || $client->skype)
                                <hr>
                                @if ($client->facebook)
                                    <a href="{{ $client->facebook }}" class="btn bg-primary mr-1" target="_BLANK"><i class="fab fa-facebook"></i></a>
                                @endif
                                @if ($client->instagram)
                                    <a href="{{ $client->instagram }}" class="btn bg-fuchsia color-palette mr-1" target="_BLANK"><i class="fab fa-instagram"></i></a>
                                @endif
                                @if ($client->linekedin)
                                    <a href="{{ $client->linekedin }}" class="btn bg-lightblue color-palette mr-1" target="_BLANK"><i class="fab fa-linkedin"></i></a>
                                @endif
                                @if ($client->skype)
                                    <a href="{{ $client->skype }}" class="btn bg-cyan color-palette mr-1" target="_BLANK"><i class="fab fa-skype"></i></a>                                 
                                @endif
                                @if ($client->twitter)
                                    <a href="{{ $client->twitter }}" class="btn bg-info color-palette mr-1" target="_BLANK"><i class="fab fa-twitter"></i></a>
                                @endif
                                <hr>                                
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-md-7">
                    <div class="card card-primary card-outline">
                        <div class="card-header">Activity Feed</div>
                        <div class="card-body">
                        </div>
                    </div>
                </div>
            </div>         
        </div>
    </section>
</div>
@endsection

@push('scripts')
    <!-- Toastr -->
    <script src="{{ asset('js/toastr.js') }}"></script>
@endpush