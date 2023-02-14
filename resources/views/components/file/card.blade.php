<div class="card card-primary card-outline">
    @if($displayHeader)
        <div class="card-header">Files</div>
    @endif
    <div class="card-body">
        <x-file.uploader :$uploadFormRoute />
        <hr>
        <x-file.list :$files />
    </div>
</div>