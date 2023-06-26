<div>
    @if ($comment->user->trashed())
        <img class="img-circle ml-3" src="{{ asset('dist/img/user_deleted.png') }}" alt="{{ $comment->user->full_name }} (deleted)" style="width:35px;height:35px;" data-toggle="tooltip" title="{{ $comment->user->full_name }} (deleted)">
    @else
        <img class="img-circle ml-3" src="{{ ($comment->user->avatar ?? false) ? asset('storage/' . $comment->user->avatar->path) : asset('dist/img/user.png') }}" alt="{{ $comment->user->full_name }}" style="width:35px;height:35px;" data-toggle="tooltip" title="{{ $comment->user->full_name }}">
    @endif        
    <div class="timeline-item">
        <span class="time"><i class="fas fa-clock"></i> {{ $comment->created_at->diffForHumans() }}</span>
        <h3 class="timeline-header">@if ($comment->user->trashed()){{ $comment->user->full_name.' (deleted)' }}@else<a href="{{ route('users.show', $comment->user) }}">{{ $comment->user->fullname }}</a>@endif</h3>
        <div class="timeline-body">
            <div id="content-comment-{{ $comment->id }}">
                {!! $comment->content !!}
                @if ($comment->files->count() > 0)
                    <ul class="list-group">
                        @foreach ($comment->files as $file)
                            <a href="{{ asset('storage/' . $file->path) }}" download="{{ $file->file_name }}">
                                <li class="list-group-item">{{ $file->file_name }}</li>                                                        
                            </a>
                        @endforeach
                    </ul>
                @endif
            </div>
            <x-comment.edit :$comment />
        </div>
        <div class="timeline-footer" id="footer-comment-{{ $comment->id }}">
            <a class="btn btn-primary btn-sm" onclick="updateContentView('edit', {{ $comment->id }})"><i class="fas fa-pencil-alt mr-1"></i>Edit</a>
            <a href="#" class="btn btn-sm btn-danger" onclick="deleteComment('{{ $comment->destroy_route }}')"><i class="fas fa-trash mr-1"></i>Delete</a>
        </div>
    </div>
</div>