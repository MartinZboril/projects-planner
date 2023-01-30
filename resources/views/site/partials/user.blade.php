<a href="{{ route('users.detail', $user->id) }}">
    <img class="img-circle" src="{{ $user->avatar ? asset('storage/' . $user->avatar->path) : asset('dist/img/user.png') }}" alt="{{ $user->full_name }}" style="width:35px;height:35px;" data-toggle="tooltip" title="{{ $user->full_name }}">
</a>