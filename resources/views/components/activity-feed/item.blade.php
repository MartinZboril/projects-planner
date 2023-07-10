<div class="card-comment">
    @if ($activity->causer->trashed())
        <img class="img-circle img-sm" src="{{ asset('dist/img/user_deleted.png') }}" alt="{{ $activity->causer->full_name }} (deleted)" style="width:35px;height:35px;" data-toggle="tooltip" title="{{ $activity->causer->full_name }} (deleted)">
    @else
        <img class="img-circle img-sm" src="{{ ($activity->causer->avatar ?? false) ? asset('storage/' . $activity->causer->avatar->path) : asset('dist/img/user.png') }}" alt="{{ $activity->causer->full_name }}" style="width:35px;height:35px;" data-toggle="tooltip" title="{{ $activity->causer->full_name }}">
    @endif
    <div class="comment-text">
        <span class="username">
            @if ($activity->causer->trashed()){{ $activity->causer->full_name.' (deleted)' }}@else<a href="{{ route('users.show', $activity->causer) }}">{{ $activity->causer->fullname }}</a>@endif
            <span class="text-muted float-right">{{ $activity->created_at->diffForHumans() }}</span>
        </span>
        {!! $activity->description !!}
    </div>
</div>
