<div class="card card-primary card-outline">
    <div class="card-header">Profile</div>
    <div class="card-body">
        <div class="row mb-3">
            <img src="{{ ($client->logo ?? false) ? asset('storage/' . $client->logo->path) : asset('dist/img/user.png') }}" class="img-circle mr-3" alt="Client Image" style="width: 75px;height: 75px;">
            <div class="">
                <h5>{{ $client->name }}</h5>
                <i class="fas fa-envelope mr-2"></i><a href="mailto:{{ $client->email }}">{{ $client->email }}</a><br>
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
            <li class="list-group-item"><i class="fas fa-pager mr-2"></i></i>Website: @if ($client->socialNetwork->website)<a href="{{ $client->socialNetwork->website }}" target="_BLANK">{{ $client->socialNetwork->website }}</a>@else{{ 'NaN' }}@endif</li>
            <li class="list-group-item"><i class="fas fa-envelope mr-2"></i>Email: <a href="mailto:{{ $client->email }}">{{ $client->email }}</a></li>
            <li class="list-group-item"><i class="fas fa-map mr-2"></i>Address: {{ $client->address->street_label }} {{ $client->address->house_number_label }}; {{ $client->address->city_label }}; {{ $client->address->zip_code_label }}; {{ $client->address->country_label }}
        </ul>
        @if ($client->socialNetwork->facebook || $client->socialNetwork->twitter || $client->socialNetwork->instagram || $client->socialNetwork->linkedin || $client->socialNetwork->skype)
            <hr>
            @if ($client->socialNetwork->facebook)
                <a href="{{ $client->socialNetwork->facebook }}" class="btn bg-primary mr-1" target="_BLANK"><i class="fab fa-facebook"></i></a>
            @endif
            @if ($client->socialNetwork->instagram)
                <a href="{{ $client->socialNetwork->instagram }}" class="btn bg-fuchsia color-palette mr-1" target="_BLANK"><i class="fab fa-instagram"></i></a>
            @endif
            @if ($client->socialNetwork->linkedin)
                <a href="{{ $client->socialNetwork->linkedin }}" class="btn bg-lightblue color-palette mr-1" target="_BLANK"><i class="fab fa-linkedin"></i></a>
            @endif
            @if ($client->socialNetwork->skype)
                <a href="{{ $client->socialNetwork->skype }}" class="btn bg-cyan color-palette mr-1" target="_BLANK"><i class="fab fa-skype"></i></a>                                 
            @endif
            @if ($client->socialNetwork->twitter)
                <a href="{{ $client->socialNetwork->twitter }}" class="btn bg-info color-palette mr-1" target="_BLANK"><i class="fab fa-twitter"></i></a>
            @endif
            <hr>                                
        @endif
    </div>
</div>