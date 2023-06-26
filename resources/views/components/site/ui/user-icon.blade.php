@if ($user)
    @if ($user->trashed())
        <img class="img-circle" src="{{ asset('dist/img/user_deleted.png') }}" alt="{{ $user->full_name }} (deleted)" style="width:35px;height:35px;" data-toggle="tooltip" title="{{ $user->full_name }} (deleted)">      
    @else
        <a href="{{ route('users.show', $user) }}">
            <img class="img-circle" src="{{ $user->avatar ? asset('storage/' . $user->avatar->path) : asset('dist/img/user.png') }}" alt="{{ $user->full_name }}" style="width:35px;height:35px;" data-toggle="tooltip" title="{{ $user->full_name }}">
        </a>        
    @endif
@else
    NaN
@endif