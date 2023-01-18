<div>
    <img class="img-circle ml-3" src="{{ $comment->user->avatar_path ? asset('storage/' . $comment->user->avatar_path) : asset('dist/img/user.png') }}" alt="{{ $comment->user->full_name }}" style="width:35px;height:35px;" data-toggle="tooltip" title="{{ $comment->user->full_name }}">
    <div class="timeline-item">
        <span class="time"><i class="fas fa-clock"></i> {{ $comment->created_at->diffForHumans() }}</span>
        <h3 class="timeline-header"><a href="{{ route('users.detail', $comment->user->id) }}">{{ $comment->user->fullname }}</a></h3>
        <div class="timeline-body">
            <div id="content-comment-{{ $comment->id }}">
                {!! $comment->content !!}
            </div>
            @include('comments.forms.update', ['comment' => $comment])
        </div>
        <div class="timeline-footer" id="footer-comment-{{ $comment->id }}">
            <a class="btn btn-primary btn-sm" onclick="updateContentView('edit', {{ $comment->id }})"><i class="fas fa-pencil-alt mr-1"></i>Edit</a>
        </div>
    </div>
</div>