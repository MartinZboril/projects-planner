<div class="card card-primary">
    <div class="card-header">
        <h3 class="card-title">{{ $comment->name }}</h3>
    </div>
    <div class="card-body">
        <form action="{{ route('comments.update', ['comment' => $comment->id]) }}" method="post">
            @csrf
            @method('PATCH')
            <input type="text" name="content" value="{{ $comment->content }}">  
            <input type="submit" name="save" class="btn btn-sm btn-primary mr-1" value="Save"></span>                
        </form>  
    </div>
</div>