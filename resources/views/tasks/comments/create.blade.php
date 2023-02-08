<div>
    <img class="img-circle ml-3" src="{{ Auth::user()->avatar ? asset('storage/' . Auth::user()->avatar->path) : asset('dist/img/user.png') }}" alt="{{ Auth::user()->full_name }}" style="width:35px;height:35px;" data-toggle="tooltip" title="{{ Auth::user()->full_name }}">
    <div class="timeline-item">
        <h3 class="timeline-header"><a href="{{ route('users.show', Auth::user()->id) }}">{{ Auth::user()->fullname }}</a></h3>
        <div class="timeline-body">
            <form action="{{ route('tasks.comments.store', $task) }}" method="post" enctype="multipart/form-data">
                @csrf
                @method('POST')
                @include('tasks.comments.forms.fields', ['comment' => $comment, 'type' => 'create'])
            </form>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#content').summernote({
                height: 200
            });
        });
    </script>
@endpush