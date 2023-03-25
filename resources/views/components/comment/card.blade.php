<div class="card card-primary card-outline">
    @if($displayHeader)
        <div class="card-header">Comments</div>
    @endif
    <div class="card-body">
        <div class="timeline">
            <x-comment.create :$storeFormRoute />
            @foreach ($comments as $comment)
                <x-comment.item :$comment />
            @endforeach
        </div>                                                        
    </div>
</div>