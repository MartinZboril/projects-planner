<a href="{{ route('users.detail', $user->id) }}">
    <img class="img-circle" src="{{ asset('dist/img/user.png') }}" alt="{{ $user->full_name }}" style="width:35px;height:35px;" data-toggle="tooltip" title="{{ $user->full_name }}">
</a>