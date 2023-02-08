<div>
    <img class="img-circle ml-3" src="{{ $comment->user->avatar ? asset('storage/' . $comment->user->avatar->path) : asset('dist/img/user.png') }}" alt="{{ $comment->user->full_name }}" style="width:35px;height:35px;" data-toggle="tooltip" title="{{ $comment->user->full_name }}">
    <div class="timeline-item">
        <span class="time"><i class="fas fa-clock"></i> {{ $comment->created_at->diffForHumans() }}</span>
        <h3 class="timeline-header"><a href="{{ route('users.show', $comment->user->id) }}">{{ $comment->user->fullname }}</a></h3>
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
            @include($editFormPartial, ['comment' => $comment])
        </div>
        <div class="timeline-footer" id="footer-comment-{{ $comment->id }}">
            <a class="btn btn-primary btn-sm" onclick="updateContentView('edit', {{ $comment->id }})"><i class="fas fa-pencil-alt mr-1"></i>Edit</a>
        </div>
    </div>
</div>