<div class="card card-primary card-outline">
    <div class="card-header">Profile</div>
    <div class="card-body">
        <div class="text-center">
            <h4>{{ $user->full_name }}</h4>
            <h5>{!! $user->role_label !!}</h5>
            <img src="{{ $user->avatar ? asset('storage/' . $user->avatar->path) : asset('dist/img/user.png') }}" class="img-circle mb-2" alt="User Image" style="width: 100px;height: 100px;">
        </div>
        <table class="table">
            <tbody>
                <tr>
                    <td class="text-muted">Email</td><td class="text-right"><a href="mailto:{{ $user->email }}">{{ $user->email }}</a></td>
                </tr>
                <tr>
                    <td class="text-muted">Username</td><td class="text-right">{{ $user->username }}</td>
                </tr>
                <tr>
                    <td class="text-muted">Job title</td><td class="text-right">{{ $user->job_title_label }}</td>
                </tr>
                <tr>
                    <td class="text-muted">Phone</td><td class="text-right">@if($user->phone)<a href="tel:{{ $user->phone }}">{{ $user->phone_label }}</a>@else{{ $user->phone_label }}@endif</td>
                </tr>                
                <tr>
                    <td class="text-muted">Mobile</td><td class="text-right">@if($user->mobile)<a href="tel:{{ $user->mobile }}">{{ $user->mobile_label }}</a>@else{{ $user->mobile_label }}@endif</td>
                </tr>
                <tr>
                    <td class="text-muted">Address</td><td class="text-right">
                        @if($user->address->street_label === 'NaN' && $user->address->house_number_label === 'NaN')
                            NaN
                        @else
                            {{ $user->address->street_label }} {{ $user->address->house_number_label }}
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="text-muted">City</td><td class="text-right">{{ $user->address->city_label }}</td>
                </tr>
                <tr>
                    <td class="text-muted">ZIP code</td><td class="text-right">{{ $user->address->zip_code_label }}</td>
                </tr>
                <tr>
                    <td class="text-muted">Country</td><td class="text-right">{{ $user->address->country_label }}</td>
                </tr>
                <tr>
                    <td class="text-muted">Verified At</td><td class="text-right">{{ $user->email_verified_at ? $user->email_verified_at->format('d.m.Y H:i') : 'Not verified' }}</td>
                </tr>
                <tr>
                    <td class="text-muted">Created At</td><td class="text-right">{{ $user->created_at->format('d.m.Y H:i') }}</td>
                </tr>
                <tr>
                    <td class="text-muted">Updated</td><td class="text-right">{{ $user->updated_at->format('d.m.Y H:i') }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>